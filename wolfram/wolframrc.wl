(* set the context to the current notebook *)
SetOptions[EvaluationNotebook[], CellContext -> Notebook]
SetDirectory[NotebookDirectory[]]
BayesianLinearRegression = ResourceFunction["BayesianLinearRegression"];

(* Text functions *)
Clear[dsFormatText]
dsFormatText[text_String : "**Markdown** text will be *formatted* with ``", data_List : {"data"}, opts : OptionsPattern[]] := dsFormatText[text, data, opts] = 
	With[{f = CreateTemporary[]}, Module[{newText},
		WriteString[f, TemplateApply[text, data]];
		newText = Import[f, {"Markdown", "FormattedText"}];
		DeleteFile[f];
		Style[newText, FilterRules[{opts}, Options[Style]]]
]];

Clear[dsColumnDelete]
dsColumnDelete[a_, cols_Integer] := Module[{},
	Drop[a, None, {cols}] (* faster than Delete[Transpose[m],cols]//Transpose;*)
];
dsColumnDelete[a_, cols_List] := Module[{},
	Drop[a, None, cols](* faster than Delete[Transpose[m],Map[{#}&,cols]]//Transpose;*)
];
Clear[dsColumnInsert]
dsColumnInsert[data_, colName_, newCol_] := Module[{newData},
   newData = data;
   newData[[All, colName]] = newCol;
   newData
];
dsColumnRename[data_,oldName_,newName_] := KeyMap[# /. oldName -> newName & #] & /@ data;

(* Data cleanup *)
Clear[dsCleanColumnNames]
dsCleanColumnNames[data_, opts: OptionsPattern[]]:=Module[{namesRow, out},
	namesRow = Lookup[{opts}, "namesRow", 1];
	out = Prepend[data[[namesRow + 1 ;;]],
	MapIndexed[If[StringQ[#], #, "column" <> ToString@#2[[1]]] &, 
		data[[namesRow]]]];
	Prepend[out[[2 ;;]], 
		StringReplace[#, RegularExpression["[\\s_](\\w)"]:>ToUpperCase["$1"]]&/@ out[[1]]
]];

Clear[dsFormatData]
dsFormatData[data_, opts: OptionsPattern[]]:=Module[{namesRow},
	namesRow=Lookup[{opts},"namesRow", 1];
	Tabular[data[[namesRow+1 ;;]], data[[namesRow]]] // Dataset // Normal
]

Clear[dsApplyFilters]
dsApplyFilters[data_, opts : OptionsPattern[]] := Module[{applyFilters},
	applyFilters=Lookup[{opts},"applyFilters", {}];
	If[applyFilters=={},
		data,
		Select[applyFilters][data]
	]
]

Clear[dsStandardizeMissing]
dsStandardizeMissing[data_] := Replace[data,""|"NA"|"<na>"|Missing["NotAvailable"]->Missing[],Infinity]
Clear[dsRemoveNonnumericRows]
dsRemoveNonnumericRows[data_] := Module[{},
	<| # -> Delete[obVals[[#]], Position[NumericQ[#] &/@ obVals[["column1"]], False]] &/@ Keys[obVals] |>
];
Clear[dsHandleMissing]
dsHandleMissing[d_] := Map[If[MemberQ[#, _Missing, Infinity], Missing[], #]&, d]

dsStandardPipeline[data_, opts: OptionsPattern[]]:=Module[{namesRow, dropMissing},
	namesRow=Lookup[{opts},"namesRow", 1];
	dropMissing=Lookup[{opts},"dropMissing", False];
	(* applyFilters=Lookup[{opts},"applyFilters", False]; *)
	RightComposition[
		dsCleanColumnNames
		,dsStandardizeMissing
		(* TODO *)
		(* If[dropMissing,dsHandleMissing], *)
		,dsFormatData
		(* dsApplyFilters *)
	][data,"namesRow"->namesRow, "applyFilters"->applyFilters]
];

(* Data summary *)
Clear[dsColumnSummaryNumeric]
dsColumnSummaryNumeric[col_List] := Module[{colClean},
	colClean = DeleteMissing[col];
	Return[{
		Min[colClean],
		Quantile[colClean, 1/4],
		Median[colClean],
		Mean[colClean],
		Quantile[colClean, 3/4],
		Max[colClean],
		StandardDeviation[colClean],
		Count[col, _Missing]
	}]
];

Clear[dsSummaryNumeric]
dsSummaryNumeric[data_] := Module[{numCols, colNames, summaries, summWithHeadings},
	numCols = Map[Style[#, Bold] &, {"min", "25%", "median", "mean", "75%", "max", "std", "#NA"}];
	colNames = Prepend[Map[Style[#, Bold] &, First@Keys[data]],""];
	summaries = N@dsColumnSummaryNumeric[data[[All,#]]] &/@ First@Keys[data];
	summWithHeadings = Prepend[summaries, numCols];
	Return[Insert[Transpose[summWithHeadings], colNames, 1]]
];

Clear[dsReplaceMissing]
dsReplaceMissing[data_, column_, type_:Median]:=Module[{newData,newValues},
	newData = data;
	newData[[All, column]] = data[[All, column]] /. Missing[] -> type[DeleteMissing@data[[All, column]]];
	newData
];

Clear[dsColumnSummaryCategorical]
dsColumnSummaryCategorical[col_List] := Module[{categories, counts},
	categories = Sort@DeleteMissing@DeleteDuplicates@col;
	counts = Map[Count[DeleteMissing[col], #] &, categories];
	Append[counts, {"#N/A",Count[col, _Missing]}]
];

Clear[dsColumnDisplayCategorical]
dsColumnDisplayCategorical[col_List] := Module[{categories, catCols},
	categories = DeleteMissing@Union@col[[2 ;;]];
	catCols = Map[Style[#, Bold, FontSize -> 16] &, Append[categories, "#NA"]];
	Return[{
		Join[{""}, catCols],
		Flatten[{
				Style[col[[1]], Bold, FontSize -> 16], 
				dsColumnSummaryCategorical[col[[2 ;;]]]
			}],
		Table["", {Length[catCols] + 1}]
	}]
];

Clear[dsSummaryCategorical]
dsSummaryCategorical[data_] := Module[{summaries},
	summaries = Tally[Flatten@Values@data];
	{Style[#[[1]], Bold,FontSize->16], #[[2]]} &/@ summaries
];

Clear[dsColumnNumericQ]
dsColumnNumericQ[col_List] := AllTrue[DeleteMissing[col], NumericQ];

Clear[dsSummary]
dsSummary[data_] := Module[{totalPts, totalNAs, numDataPositions, catDataPositions},
	(* TODO fix parenthical removal on keys *)
	totalPts = Length@data;
	totalNAs = Length@Select[Values@data, MemberQ[#, Missing[]] &];
	numDataPositions = Position[AllTrue[DeleteMissing[#], NumericQ] & /@ (data[[All, #]] & /@ Keys[data[[1]]]), True] // Flatten;
	catDataPositions = Position[AllTrue[DeleteMissing[#], NumericQ] & /@ (data[[All, #]] & /@ Keys[data[[1]]]), False] // Flatten;
	{
		{{Style["# points", Bold], totalPts}},
		{{Style["# NA points", Bold], totalNAs}},
		{""},
		If[Length@numDataPositions == 0, {}, dsSummaryNumeric@data[[All,numDataPositions]]],
		{""},
		If[Length@catDataPositions == 0, {}, dsSummaryCategorical@data[[All,catDataPositions]]]
	} // TableForm
];

dsTestLinearModelFits[data_, colsToCheck_List, nominalVariable_ : 0] := Module[{},
	LinearModelFit[data[[All, Symbol[#] & /@ colsToCheck]],
	Prepend[Symbol["x" <> Capitalize[#]] & /@ Drop[colsToCheck, -1], 1],
	Symbol["x" <> Capitalize[#]] & /@ Drop[colsToCheck, -1],
	NominalVariables -> If[nominalVariable == 0, None, Symbol["x" <> Capitalize@nominalVariable]]
(*
LinearModelFit[
   Values@housingFiltered[[All, #]],
   Prepend[Symbol /@ #[[;; -2]], 1],
   Symbol /@ #[[;; -2]]
] & /@ {
  {"medianIncome", "roomsPerHouse", "medianHouseValue"},
  {"housingMedianAge", "roomsPerHouse", "medianHouseValue"}
}
 *)
]];

Clear[dsPlotStdDevRegion]
dsPlotStdDevRegion[p_,data_,xmin_,xmax_]:=Module[{},
	Show[
		Plot[{
				p[x],
				p[x]+StandardDeviation[p[x,"Distribution"]],p[x]-StandardDeviation[p[x,"Distribution"]]
			},{x,xmin,xmax},
			PlotStyle->{Blue,Gray,Gray},
			Filling->{2->{3}},
			PlotLegends->{"Prediction","Confidence Interval"}],
		ListPlot[data,PlotStyle->Red,PlotLegends->{"Data"}]
]];

dsScatterPlotMatrix[groupedValues_, features_, valueClasses_List, lower_ : 0, upperLim_ : 0] := Module[{classes, pos, legend, upper},
	upper = If[upperLim == 0, Max@DeleteCases[dataSources // Values // Normal // Values // Flatten, _String], upperLim];
	classes = Union[valueClasses];
	legend = PointLegend[
		ColorData[97, "ColorList"][[1 ;; Length[classes]]],
		classes,
		LegendLayout -> "Row",
		LegendMarkers -> {"OpenMarkers", Small},
		LegendFunction -> "Frame",
		LegendLabel -> "Classes"
	];
	Column[{
		Table[If[i == j, features[[i]], If[i > j, ListPlot[
				Table[
					Transpose[{groupedValues[[c, All, features[[i]]]] // Normal, groupedValues[[c, All, features[[j]]]] // Normal}],
					{c, classes}
				],
				PlotMarkers -> {"OpenMarkers", Small},
				PlotRange -> {{lower, upper}, {lower, upper}}]],
			{}],
			{i, Range@Length@features},
			{j, Range@Length@features}
		] // Grid,
		legend
	}, Alignment -> Center]
];

Clear[dsLinearModelFit]
dsLinearModelFit[data_, column1_, column2_] := Module[{},
	LinearModelFit[Values@data[[All, {column1, column2}]], {1, x}, x]
];

Clear[dsLogitModelFit]
dsLogitModelFit[data_, column1_, column2_] := Module[{model,outliers},
	model = LogitModelFit[Values@data[[All, {column1, column2}]], x, x];
	outliers = dsGetOutliers[model];
	If[Length[outliers]>0,
		Print["Outliers found: ", outliers], Print[]
	];
	model
];

Clear[dsGetOutliers]
dsGetOutliers[model_, opts: OptionsPattern[]] := Module[{threshold},
	threshold=Lookup[{opts},"threshold", .75];
	Select[
		Normalize@model["CookDistances"],
		# > threshold & -> "Index"
	]
];
Clear[dsShowOutliers]
dsShowOutliers[model_,opts:OptionsPattern[]] := Module[{threshold},
	threshold=Lookup[{opts},"threshold", .25];
	BarChart[
		MapIndexed[If[#1 > threshold, Callout[#1, #2[[1]]], #1] &, 
		model["CookDistances"]]
	]
]

dsHilightOutlierLinear[data_, column1_, column2_, opts: OptionsPattern[]] := Module[{linearModel},
	linearModel = dsLinearModelFit[data,column1,column2];
	dsHilightOutlier[data, column1, column2, linearModel, opts]
]
dsHilightOutlierLogit[data_, column1_, column2_, opts: OptionsPattern[]] := Module[{logitModel},
	logitModel = dsLogitModelFit[data,column1,column2];
	dsHilightOutlier[data, column1, column2, logitModel, opts]
]
Clear[dsHilightOutlier]
dsHilightOutlier[data_, column1_, column2_, model_, opts: OptionsPattern[]] := Module[{threshold},
	threshold=Lookup[{opts},"threshold", .75];
	outlierIndex = dsGetOutliers[model, "threshold"->threshold];
	Show[
		Plot[{
				model[x],
				model["MeanPredictionBands"],
				model["SinglePredictionBands"]
			}
			,{x, 0, Max@data[[All, column1]]}
			,AxesLabel -> {column1, column2}
			,Filling -> {2}
		],
		ListPlot@Values@data[[All, {column1, column2}]],
		Graphics[{
			Red
			,PointSize[Large]
			,Point[Values@data[[outlierIndex, {column1, column2}]]]
		}]
	]
];

(* TODO *)
dsMeanInWindow[data_, beg_, end_] := Module[{dataInWindow},
   dataInWindow = Select[data, beg <= #1[[1]] < end &];
   N[If[Length[dataInWindow] === 0, 0, Mean[dataInWindow[[All, 2]]]]]
];

dsBinnedProbabilities[data_List, range_List, nBins_Integer:20] := Module[{dx,min,max},
	{min,max}=range;
   dx = (max - min)/nBins;
   N[Table[
   	{x, dsMeanInWindow[data, x - dx/2, x + dx/2]},
		{x, min + dx/2, max - dx/2, dx}]]
];


(* Machine learning *)
dsTrainingTestDataSplit[data_] := Module[{trainingData},
	trainingData = RandomSample[data, Floor[Length@data*0.8]];
	<|"training" -> trainingData, "test" -> Complement[data, trainingData]|>
];

dsTrain[data_,result_:-1]:=Module[{trainingData,testData},
	trainingData = RandomSample[data, Floor[Length@data*0.8]];
	testData = Complement[data, trainingData];
	<|
		"training"->trainingData[[All, Drop[Keys@trainingData, result] ]] -> trainingData[[All, result]],
		"test" -> testData[[All, Drop[Keys@testData, result] ]] -> testData[[All, result]]
	|>
]

dsClassify[trainingData_,result_:-1]:=Module[{},
	(* TODO *)
	Classify[trainingData[[All, Drop[Keys@trainingData, result] ]] -> trainingData[[All, result]]]
];


dsTPRAndFPR :=
 (TableForm[{{"TPR", N@Divide[#[[1, 1]], Total@#[[1]]]},
     {"FPR", N@Divide[#[[2, 1]], Total@#[[2]]]}}] &)
dsRocSpace := ({
    Divide[#[[1, 1]], Total@#[[1]]],
    Divide[#[[2, 1]], Total@#[[2]]]
    } &)

Clear[dsRocPlot]
dsRocPlot[t_] := Module[{tpr, fpr},
  tpr = Divide[t[[1, 1]], Total@t[[1]]];
  fpr = Divide[t[[2, 1]], Total@t[[2]]];
  Show[
		Plot[
			x, {x, 0, 1}, PlotRange -> {{0, 1}, {0, 1}}, 
			PlotStyle -> {Black, Thick}, AspectRatio -> 1, Frame -> True, 
			FrameLabel -> {"FPR = " <> ToString[N@fpr], "TPR (recall or sensitivity) = " <> ToString[N@tpr]}, 
			LabelStyle -> Directive[Medium], Filling -> Top, 
			FillingStyle -> LightBlue],
		Labelled[Graphics[{Red, PointSize[Large], Point[{fpr, tpr}]}], "FPR = " <> ToString[N@fpr] <>"\nTPR (recall or sensitivity) = " <> ToString[N@tpr]]
	]
];

dsRocPlot[t_List] := Module[{},
	Show[
   	Plot[x, {x, 0, 1}, PlotRange -> {{0, 1}, {0, 1}}, 
			PlotStyle -> {Black, Thick}, AspectRatio -> 1, Frame -> True, 
			FrameLabel -> {"FPR", "TPR (recall or sensitivity)"}, 
			LabelStyle -> Directive[Medium], Filling -> Top, 
			FillingStyle -> LightBlue],
		Sequence[Graphics[{Red, PointSize[Large],
			Tooltip[Point[{Divide[#[[2, 1]], Total@#[[2]]], Divide[#[[1, 1]], Total@#[[1]]]}],
         "FPR = " <> ToString[N@Divide[#[[2, 1]], Total@#[[2]]]] <>
         "\nTPR (recall or sensitivity) = " <> ToString[N@Divide[#[[1, 1]], Total@#[[1]]]]
         ]}
		] & /@ If[Length@Dimensions[t] > 2, t, {t}]]
]];

Clear[dsClassifierProbTable]
dsClassifierProbTable[classifier_, testData_, cmeasures_, cl_] := 
 Module[{probabilityClass, sortedProbabilities, ordered, 
   sortedTestclass, sortedPredictedclass},
  probabilityClass = 
   classifier[testData[[All, 1]], "Probability" -> cl];
  sortedProbabilities = Reverse@Sort@probabilityClass;
  ordered = Reverse@Ordering@probabilityClass;
  sortedTestclass = testData[[ordered]][[All, 2]];
  sortedPredictedclass = classifier[testData[[All, 1]]][[ordered]];
  {
    TableForm[
     Transpose[{sortedTestclass, sortedProbabilities}],
     TableHeadings -> {{}, {"True class", "Probability to be " <> cl}}
     ],
    cmeasures["ROCCurve"][[1]]
    } // TableForm
];

Clear[dsViewClassification]
dsViewClassification[data1_, data2_, method_: "LogisticRegression", classifier_:{}] :=
   Module[{c, line, points, pred},
   If[Head[classifier]==List,
   	c = Classify[<|"class 1" -> data1, "class 2" -> data2|>, Method -> method],
   	c = classifier
   ];
   line = Range[0, 5, 0.05];
   points = Tuples[line, 2];
   pred = c[points];
   temp = Tally[Sort[pred]];
   Show[
    ListPlot[{points[[Ordering[pred]]][[1 ;; temp[[1, 2]]]], points[[Ordering[pred]]][[temp[[1, 2]] + 1 ;; Length[pred]]]}, 
	     PlotStyle -> {{RGBColor[0.97, 1., 0.55], PointSize[0.015]}, {RGBColor[0.5, 1, 0.5], PointSize[0.015]}}, PlotLegends -> {"Predicted 1", "Predicted 2"}],
    ListPlot[{class1, class2}, PlotStyle -> PointSize[Large], PlotLegends -> {"Class 1", "Class 2"}], 
    ListPlot[{unknown}, PlotStyle -> {Red, PointSize[0.03]}, 
			PlotLegends -> {"unclassified"}], Graphics@Text[c[unknown]]
    ]
];

Clear[dsCheckTimeSeriesModel]
dsCheckTimeSeriesModel[model_]:=Module[{},
	Column[{
		GraphicsRow[{
			ListLinePlot[#["FitResiduals"],PlotRange->All],
			#["ACFPlot"],
			#["PACFPlot"]
		}, ImageSize->Full],
		{
			AutocorrelationTest[#["FitResiduals"]],
			"  ---  is correlated (BAD) "->AutocorrelationTest[#["FitResiduals"]]<0.05
		}//Row
		}]&/@{model}//First
];

Clear[dsMakeTimeSeriesRegular]
dsMakeTimeSeriesRegular[ts_] := Module[{regularTimeIntervals},
   regularTimeIntervals = Range[
     (*start*)ts["FirstTime"],
     (*end*)ts["LastTime"],
     (*steps*)(ts["LastTime"] - ts["FirstTime"])/(Length[Values@ts] - 1)
   ];
   TimeSeries[{#, ts[#]} & /@ regularTimeIntervals]
];

Clear[dsForecastWithUncertainty]
dsForecastWithUncertainty[data_,modelIn_,startTime_,forecastTime_,manualRangeAdjust_ : 0] := Module[{model,handoffTime, rangeAdjust},
	model = If[Equal[ToString@Head@modelIn, "TimeSeriesModel"], modelIn, TimeSeriesModelFit[data, modelIn]];
   handoffTime = First@Floor@data["LastTimes"];
   rangeAdjust = Subtract @@ Reverse@MinMax@Values@data;
   rangeAdjust = rangeAdjust + manualRangeAdjust;
   Show[
   	ListLinePlot[data, PlotRange -> {{startTime, forecastTime}, {data[startTime] - rangeAdjust, model[forecastTime] + rangeAdjust}}],
		Plot[{
		      model[t],
		      model["PredictionLimits"][t]
	      },
	      {t, handoffTime, forecastTime},
			PlotStyle -> {Dashed, Gray}, Filling -> {1 -> {2}}, 
			FillingStyle -> LightBlue
		]
]];

Clear[dsMakeInitializedTimesSeriesModel]
dsMakeInitializedTimesSeriesModel[data_,model_]:=Module[{},
	Insert[model["BestFit"], data, -1]
];

Clear[dsRemovePeriodicAverages]
dsRemovePeriodicAverages[tseries_,periodLength_:12]:=Module[{cyclicalAverages},
	cyclicalAverages=Mean@Partition[Values@tseries,periodLength];
	tseries-TimeSeries[
		Flatten[Table[cyclicalAverages, Ceiling[Subtract[Sequence@@Reverse@MinMax@Keys@tseries]] * periodLength]][[;;Length@Values@tseries]],
		{Keys@tseries}
	]
];

Clear[dsSplitTree]
dsSplitTree[data_,splitValue0_:-100]:=Module[{output,splitValue},
	splitValue=If[splitValue0 == -100,Mean@data[[All,1]],splitValue0];
	output=<|"splitValue"->splitValue|>;
	output=Insert[output,{
		"leftPosition"->Flatten@Position[data[[All,1]],_?(#<=splitValue&)],
		"rightPosition"->Flatten@Position[data[[All,1]],_?(#>splitValue&)]
	},-1];
	output=Insert[output,{
		"leftValue"->Mean[data[[output[["leftPosition"]],2]]],
		"rightValue"->Mean[data[[output[["rightPosition"]],2]]]
	},-1];
	output=Insert[output,"jLoss"->Total[
		(data[[output[["leftPosition"]],2]]-output[["leftValue"]])^2]
		+ Total[(data[[output[["rightPosition"]],2]]-output[["rightValue"]])^2],-1];
	Return[output]
];

Clear[dsKmcFindCenters]
dsKmcFindCenters[data_, opts: OptionsPattern[]]:=Module[{kCategories,currentCentroids,distToCenters,tempClusters,loops},
	kCategories=Lookup[{opts},"kCategories", 2];
	loops=Lookup[{opts},"loops", 5];
	currentCentroids=Lookup[{opts},"startingCentroids", RandomSample[data, kCategories]];
   Do[
      distToCenters = Table[
         EuclideanDistance[data[[i]], currentCentroids[[#]]] & /@ Range@kCategories,
         {i, 1, Length[data]}
      ];
      assigned = Flatten[Table[
         Position[distToCenters[[i]], Min[distToCenters[[i]]]],
         {i, 1, Length[data]}
      ]];
      tempClusters = Table[
         data[[ Flatten[Position[assigned, j]] ]],
         {j,Range@kCategories}
      ];
      currentCentroids = Table[
         {Mean[tempClusters[[j, All, 1]]], Mean[tempClusters[[j, All, 2]]]},
         {j, Range@kCategories}];
   , loops];
   currentCentroids
];

Clear[dsErrorForKmcSize]
dsErrorForKmcSize[data_,k_]:=Module[{clusters,centers,error},
	clusters=FindClusters[data, k, Method->"KMeans"];
	centers=Table[Mean[clusters[[i]]],{i, Range@k}];
	error=Total[Table[
			EuclideanDistance[clusters[[i, j]], centers[[i]]]^2,
			{i, 1, Length[centers]},
			{j, 1, Length[clusters[[i]]]}
		],All];
	error
];

Clear[dsFindBestKmcErrorRate]
dsFindBestKmcErrorRate[data_,maxK_:20]:=Module[{},
	ListPlot[Abs@Differences[
		dsErrorForKmcSize[data,#]&/@Range[maxK]
	], ImageSize -> Scaled@.4]
]

Clear[dsBaysianDistCheck]
dsBaysianDistCheck[n_,k_,numOutcomes_,searchStart_:0.01]:=Module[{probCheck},
	probCheck=1/numOutcomes;
	<|
		"unbiased probability of outcome"->N@probCheck,
		"probability of bias"->PercentForm[1-NProbability[p>=probCheck,Distributed[p,BetaDistribution[k+1,n+1-k]]]],
		"p-value"->N@Probability[p>=probCheck,Distributed[p,BetaDistribution[k+1,n+1-k]]],
		"significant p-value"->Probability[p>=probCheck,Distributed[p,BetaDistribution[k+1,n+1-k]]]<0.05,
		"starting from "<>ToString@searchStart<>" maximum at"->FindMaximum[PDF[BetaDistribution[k+1,n+1-k]][p],{p,searchStart}],
		"width/std dev"->N@StandardDeviation[BetaDistribution[k+1,n+1-k]]
	|>
];

Print["wolframrc loaded"]