(* set the context to the current notebook *)
SetOptions[EvaluationNotebook[], CellContext -> Notebook]

(* Text functions *)
Clear[formatText]
formatText[text_String : "**Markdown** text will be *formatted* with ``", data_List : {"data"}, opts : OptionsPattern[]] := formatText[text, data, opts] = 
	With[{f = CreateTemporary[]}, Module[{newText},
		WriteString[f, TemplateApply[text, data]];
		newText = Import[f, {"Markdown", "FormattedText"}];
		DeleteFile[f];
		Style[newText, FilterRules[{opts}, Options[Style]]]
]];

(* Data manipulation functions *)
Clear[columnNamesToIndexes]
columnNamesToIndexes[dataSet_, namesRow_] := Do[
	ToExpression[dataSet[[namesRow, i]], InputForm, Function[name, name = i, HoldAll]],
	{i, Length@dataSet[[namesRow]]}
];
Clear[columnNamesToKeys]
columnNamesToKeys[csv_] := AssociationThread[ToString /@ csv[[1]] -> #] & /@ csv[[2 ;;]];
Clear[columnDelete]
columnDelete[a_, cols_Integer] := Module[{},
	Print["If updating original array, be sure to re-run columnNamesToIndexes"]
	Drop[a, None, {cols}] (* faster than Delete[Transpose[m],cols]//Transpose;*)
];
columnDelete[a_, cols_List] := Module[{},
	Print["If updating original array, be sure to re-run columnNamesToIndexes"]
	Drop[a, None, cols](* faster than Delete[Transpose[m],Map[{#}&,cols]]//Transpose;*)
];
Clear[columnInsert]
columnInsert[a_, newCol_, pos_] := Module[{},
	Print["If updating original array, be sure to re-run columnNamesToIndexes"]
	Insert[Transpose[a], newCol, pos] // Transpose (* tested MapThread[Insert[#1,#2,3]&,{testData,testCol}], slower *)
];
standardizeMissing[d_] := d /. "" | "NA" | "<na>" | Missing["NotAvailable"] -> Missing[]

Print["wolframrc loaded"]
