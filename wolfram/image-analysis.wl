Clear[distance, lowPassIdeal, getFourier, lowPassGauss, 
  lowPassButterworth, reverseFourier, highPassButterworth];
centeringMatrix = 
  FunctionCompile[
   Function[{Typed[dimM, "Real64"], Typed[dimN, "Real64"]},
    Table[(-1)^(m + n), {m, 1, dimM}, {n, 1, dimN}]
    ]];
distance[{u_, v_}, {M_, N_}] := 
  distance[{u, v}, {M, N}] = Sqrt[(u - M)^2 + (v - N)^2];
getFourier[img_, willFilter_ : True] := 
  getFourier[img, willFilter] = 
   Module[{imgData, dimM, dimN, centeredImgData}, 
    imgData = ImageData@ColorConvert[img, "Grayscale"];
    {dimM, dimN} = Dimensions[imgData];
    centeredImgData = imgData*centeringMatrix[dimM, dimN];
    Fourier[If[willFilter,
      ArrayPad[centeredImgData, {{0, dimM}, {0, dimN}}],
      centeredImgData],
     FourierParameters -> {-1, 1}]];
reverseFourier[imgData_] := reverseFourier[imgData] = Module[{M, N},
    {M, N} = Dimensions[imgData];
    Image[Take[
       Re@InverseFourier[imgData, FourierParameters -> {-1, 1}],
       {1, M/2}, {1, N/2}
       ]*centeringMatrix[M/2, N/2]]
    ];

lowPassCore = 
  FunctionCompile[
   Function[{Typed[M, "Real64"], Typed[N, "Real64"], Typed[d, "Real64"]},
    Table[
     If[Sqrt[(u - M/2)^2 + (v - N/2)^2] <= d, 1, 0], {u, 1, M}, {v, 1,
       N}]
    ]];
lowPassIdeal[imgData_, d_] := lowPassIdeal[imgData, d] = Module[{M, N},
   {M, N} = Dimensions[imgData];
   imgData*lowPassCore[M, N, d]
   (*imgData*Table[If[distance[{u,v},{M/2,N/2}]<=d,1,0],{u,1,M},{v,1,
   N}]*)
   ]

gaussCore = 
  FunctionCompile@
   Function[{Typed[M, "Real64"], Typed[N, "Real64"], 
     Typed[\[Sigma], "Real64"]},
    Table[
     Power[E, 
      Divide[-Sqrt[(u - M/2)^2 + (v - N/2)^2]^2, (2 \[Sigma])^2]],
     {u, 1, M}, {v, 1, N}
     ]];
lowPassGauss[imgData_, \[Sigma]_ : 2] := 
  lowPassGauss[imgData, \[Sigma]] = Module[{M, N},
    {M, N} = Dimensions[imgData];
    imgData*gaussCore[M, N, \[Sigma]]
    (*Table[
    Power[E,Divide[-distance[{u,v},{M/2,N/2}]^2,(2\[Sigma])^2]],
    {u,1,M},{v,1,N}
    ]*)
    ];
butterworthCore = 
  FunctionCompile@
   Function[{Typed[M, "Real64"], Typed[N, "Real64"], Typed[d, "Real64"],
      Typed[n, "Real64"], Typed[isHigh, "Boolean"]},
    If[isHigh,
     Table[
      1 - Divide[1, 1 + Power[Sqrt[(u - M/2)^2 + (v - N/2)^2]/d, 2 n]],
      {u, 1, M}, {v, 1, N}
      ],
     Table[
      Divide[1, 1 + Power[Sqrt[(u - M/2)^2 + (v - N/2)^2]/d, 2 n]],
      {u, 1, M}, {v, 1, N}
      ]
     ]];
lowPassButterworth[imgData_, d_ : 20, n_ : 2] := 
  lowPassButterworth[imgData, d, n] = Module[{M, N},
    {M, N} = Dimensions[imgData];
    imgData*butterworthCore[M, N, d, n, False]
    (*imgData*Table[
    Divide[1,1+Power[distance[{u,v},{M/2,N/2}]/d,2n]],
    {u,1,M},{v,1,N}
    ]*)
    ];
highPassButterworth[imgData_, d_ : 20, n_ : 2] := 
  highPassButterworth[imgData, d, n] = Module[{M, N},
    {M, N} = Dimensions[imgData];
    imgData*butterworthCore[M, N, d, n, True]
    (*imgData*Table[
    1-Divide[1,1+Power[distance[{u,v},{M/2,N/2}]/d,2n]],
    {u,1,M},{v,1,N}
    ]*)
    ];
