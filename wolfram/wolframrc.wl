(* set the context to the current notebook *)
SetOptions[EvaluationNotebook[], CellContext -> Notebook]
SetDirectory[NotebookDirectory[]]

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
dsColumnInsert[a_, newCol_, opts: OptionsPattern[]] := Module[{position},
	position=Lookup[{opts},"position", 1];
	Transpose@Insert[Transpose[a], newCol, position]
];

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
	If[Length[applyFilters]==0,
		Return[data],
		Return[RightComposition[Sequence @@ applyFilters][data]]
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
	numCols = Map[Style[#, Bold, FontSize -> 16] &, {"min", "25%", "median", "mean", "75%", "max", "std", "#NA"}];
	colNames = Prepend[Map[Style[#, Bold, FontSize -> 16] &, First@Keys[data]],""];
	summaries = N@dsColumnSummaryNumeric[data[[All,#]]] &/@ First@Keys[data];
	summWithHeadings = Prepend[summaries, numCols];
	Return[Transpose@dsColumnInsert[summWithHeadings, colNames]]
];

Clear[dsColumnSummaryCategorical]
dsColumnSummaryCategorical[col_List] := Module[{categories, counts},
	categories = Sort@DeleteMissing@DeleteDuplicates@col;
	counts = Map[Count[DeleteMissing[col], #] &, categories];
	Append[counts, Count[col, _Missing]]
];

Clear[dsColumnDisplayCategorical]
dsColumnDisplayCategorical[col_List] := Module[{categories, catCols},
	categories = DeleteMissing@Union@col[[2 ;;]];
	catCols = Map[Style[#, Bold, FontSize -> 16] &, Append[categories, "#NA"]];
	Return[{
		Join[{""}, catCols], 
		Flatten[{Style[col[[1]], Bold, FontSize -> 16], 
		dsColumnSummaryCategorical[col[[2 ;;]]]}],
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
	LinearModelFit[data[[2 ;;, Symbol[#] & /@ colsToCheck]],
	Prepend[Symbol["x" <> Capitalize[#]] & /@ Drop[colsToCheck, -1], 1],
	Symbol["x" <> Capitalize[#]] & /@ Drop[colsToCheck, -1],
	NominalVariables -> If[nominalVariable == 0, None, Symbol["x" <> Capitalize@nominalVariable]]
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

Print["wolframrc loaded"]