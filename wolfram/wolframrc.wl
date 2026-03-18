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
	Transpose@Insert[Transpose[a], newCol, position]
];

(* Data cleanup *)
Clear[dsStandardizeMissing]
dsStandardizeMissing[data_] := Replace[data,""|"NA"|"<na>"|Missing["NotAvailable"]->Missing[],Infinity]

Clear[dsRemoveNonnumericRows]
dsRemoveNonnumericRows[data_] := Module[{},
  <| # -> Delete[obVals[[#]], Position[NumericQ[#] &/@ obVals[["column1"]], False]] &/@ Keys[obVals] |>
];

Clear[dsHandleMissing]
dsHandleMissing[d_] := Map[If[MemberQ[#, _Missing, Infinity], Missing[], #]&, d]

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
  colNames = Map[Style[#, Bold, FontSize -> 16] &, Keys[data]];
  summaries = N@dsColumnSummaryNumeric[data[[#,All]]] &/@ Keys[data];
  summWithHeadings = Prepend[summaries, numCols];
  Return[dsColumnInsert[summWithHeadings, Prepend[colNames,""]]]
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
  summaries = Tally[#] &/@ data;
  {Style[Keys[#][[1]], Bold,FontSize->16], Values@#} &/@ {summaries}
];

Clear[dsColumnNumericQ]
dsColumnNumericQ[col_List] := AllTrue[DeleteMissing[col], NumericQ];

Clear[dsSummary]
dsSummary[data_] := Module[{totalPts, totalNAs, numDataPositions, catDataPositions},
  totalPts = Length@data[[1]] - 1;
  totalNAs = Length@Select[Values@data, MemberQ[#, Missing[]] &];
  numDataPositions = Position[AllTrue[DeleteMissing[#], NumericQ] & /@ Values@data, True] // Flatten;
  catDataPositions = Position[AllTrue[DeleteMissing[#], NumericQ] & /@ Values@data, False] // Flatten;
  {
    {{Style["# points", Bold], totalPts}},
    {{Style["# NA points", Bold], totalNAs}},
    {""},
    If[Length@numDataPositions == 0, {},
      dsSummaryNumeric@data[[numDataPositions]]
    ],
    {""},
    If[Length@catDataPositions == 0, {},
      dsSummaryCategorical@data[[catDataPositions]]
    ]
  } // TableForm
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


dsTestLinearModelFits[data_, colsToCheck_List, nominalVariable_ : 0] := Module[{},
   LinearModelFit[data[[2 ;;, Symbol[#] & /@ colsToCheck]],
    Prepend[Symbol["x" <> Capitalize[#]] & /@ Drop[colsToCheck, -1], 1],
    Symbol["x" <> Capitalize[#]] & /@ Drop[colsToCheck, -1],
    NominalVariables -> If[nominalVariable == 0, None, Symbol["x" <> Capitalize@nominalVariable]]
]];

Print["wolframrc loaded"]