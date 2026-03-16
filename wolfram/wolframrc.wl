(* set the context to the current notebook *)
SetOptions[EvaluationNotebook[], CellContext -> Notebook]

(* Text functions *)
Clear[dsFormatText]
dsFormatText[text_String : "**Markdown** text will be *formatted* with ``", data_List : {"data"}, opts : OptionsPattern[]] := formatText[text, data, opts] = 
	With[{f = CreateTemporary[]}, Module[{newText},
		WriteString[f, TemplateApply[text, data]];
		newText = Import[f, {"Markdown", "FormattedText"}];
		DeleteFile[f];
		Style[newText, FilterRules[{opts}, Options[Style]]]
]];

(* Data manipulation functions *)
Clear[dsCleanColumnNames]
dsCleanColumnNames[data_, opts: OptionsPattern[]]:=Module[{namesRow, out},
  namesRow = Lookup[{opts}, "namesRow", 1];
  out = Prepend[data[[namesRow + 1 ;;]],
    MapIndexed[If[StringQ[#], #, "column" <> ToString@#2[[1]]] &, 
    data[[namesRow]]]];
  Prepend[out[[2 ;;]], 
    StringReplace[#, RegularExpression["[\\s_](\\w)"]:>ToUpperCase["$1"]]&/@ out[[1]]
]];

Clear[dsColumnNamesToIndexes]
dsColumnNamesToIndexes[data_, opts: OptionsPattern[]]:=Module[{namesRow},
  namesRow=Lookup[{opts},"namesRow", 1];
  Do[
	 ToExpression[data[[namesRow, i]], InputForm, Function[name, name = i, HoldAll]],
	 {i, Length@data[[namesRow]]}
]];

Clear[dsColumnNamesToKeys]
dsColumnNamesToKeys[data_, opts: OptionsPattern[]]:=Module[{namesRow},
  namesRow=Lookup[{opts},"namesRow", 1];
  Association@MapIndexed[#->Flatten@data[[namesRow+1;;,#2]] &, data[[namesRow]]]
]

Clear[dsRowsToObjects]
dsRowsToObjects[data_, opts: OptionsPattern[]]:=Module[{namesRow},
  namesRow=Lookup[{opts},"namesRow", 1];
  AssociationThread[ToString /@ data[[namesRow]] -> #] & /@ data[[namesRow+1 ;;]]
]

Clear[dsColumnDelete]
dsColumnDelete[a_, cols_Integer] := Module[{},
	Print["If updating original array, be sure to re-run columnNamesToIndexes"];
	Drop[a, None, {cols}] (* faster than Delete[Transpose[m],cols]//Transpose;*)
];
dsColumnDelete[a_, cols_List] := Module[{},
	Print["If updating original array, be sure to re-run columnNamesToIndexes"];
	Drop[a, None, cols](* faster than Delete[Transpose[m],Map[{#}&,cols]]//Transpose;*)
];
Clear[dsColumnInsert]
dsColumnInsert[a_, newCol_, opts: OptionsPattern[]] := Module[{position},
  position=Lookup[{opts},"position", 1];
	Print["If updating original array, be sure to re-run columnNamesToIndexes"];
	Insert[Transpose[a], newCol, position] // Transpose
  (* tested MapThread[Insert[#1,#2,3]&,{testData,testCol}], slower *)
];

(* Data cleanup *)
Clear[dsStandardizeMissing]
dsStandardizeMissing[data_] := Replace[data,""|"NA"|"<na>"|Missing["NotAvailable"]->Missing[],Infinity]

Clear[dsHandleMissing]
dsHandleMissing[d_] := Map[If[MemberQ[#, _Missing, Infinity], Missing[], #]&, d]

(* Data summary *)
Clear[dsColumnSummaryNumeric]
dsColumnSummaryNumeric[col_List] := Module[{colClean, min, qu25, median, mean, qu75, max, na},
  colClean = DeleteMissing[col];
  min = Min[colClean];
  qu25 = Quantile[colClean, 1/4];
  median = Median[colClean];
  mean = Mean[colClean];
  qu75 = Quantile[colClean, 3/4];
  max = Max[colClean];
  na = Count[col, _Missing];
  Return[{min, qu25, median, mean, qu75, max, na}]
];

Clear[dsSummaryNumeric]
dsSummaryNumeric[data_] := Module[{numCols, colNames, summ, summWithHeadings},
  numCols = Map[Style[#, Bold, FontSize -> 16] &,
    {"min", "25%", "median", "mean", "75%", "max", "#NA"}];
  colNames = Map[Style[#, Bold, FontSize -> 16] &, data[[1]]];
  summ = N@Map[numColSummary, Transpose[data][[All, 2 ;;]]];
  summWithHeadings = Prepend[summ, numCols];
  Return[insertColumn[summWithHeadings, Prepend[colNames, ""], 1]]
];

Clear[dsColumnSummaryCategorical]
dsColumnSummaryCategorical[col_List] := Module[{categories, counts},
dsCategories = Sort@DeleteMissing@DeleteDuplicates@col;
dsCounts = Map[Count[DeleteMissing[col], #] &, categories];
Append[counts, Count[col, _Missing]]   (* <-- *)
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
dsSummaryCategorical[data_] := Module[{table},
  table = Map[dsColumnDisplayCategorical, Transpose[data]];
  Return[Map[Transpose, table]]
];

Clear[dsColumnNumericQ]
dsColumnNumericQ[col_List] := AllTrue[DeleteMissing[col[[2 ;;]]], NumericQ];
Clear[dsDataSummary]
dsDataSummary[data_List] := Module[{totalPts, totalNAs, numData, catData},
  totalPts = Length[data] - 1;
  totalNAs = Length@Select[data[[2 ;;]], MemberQ[#, Missing[]] &];
  numData = Select[Transpose[data], dsColumnNumericQ];
  catData = Select[Transpose[data], Not@dsColumnNumericQ[#] &];
  Return[Join[
    {{Style["# points", Bold], totalPts}},
    {{Style["# NA points", Bold], totalNAs}},
    {""},
    If[Length[numData] == 0, {}, dsSummaryNumeric[Transpose[numData]]],
    {""},
    If[Length[catData] == 0, {}, dsSummaryCategorical[Transpose[catData]]]
  ] // TableForm]
];

dsStandardPipeline[data_, opts: OptionsPattern[]]:=Module[{namesRow, dropMissing},
  namesRow=Lookup[{opts},"namesRow", 1];
  dropMissing=Lookup[{opts},"dropMissing", False];
  RightComposition[
    dsCleanColumnNames,
    dsStandardizeMissing,
    (* TODO *)
    (* If[dropMissing,dsHandleMissing], *)
    dsColumnNamesToKeys
  ][data,"namesRow"->namesRow]
];

Print["wolframrc loaded"]
