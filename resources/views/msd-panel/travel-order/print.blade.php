<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Information System') }}</title>

    {{--
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    
    {{-- <style>
        @media screen {
            .print-only {
                display: none;
            }
        }

        @media print {

            /* itago ang app chrome sa print */
            .no-print,
            .main-header,
            .main-sidebar,
            .content-header,
            .navbar,
            .footer,
            .sidebar,
            .btn,
            .card-header {
                display: none !important;
            }

            html,
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            img {
                max-width: 100%;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            tr,
            td,
            th,
            img {
                page-break-inside: avoid;
            }

            .page-break {
                page-break-before: always;
            }
        }

    </style> --}}

</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice">
            <!-- title row -->
            <div class="text-center">
                <div class="row">
                    <div class="col-12 text-center p-0">
                        <img src="{{ asset('images/logo.png') }}" class="brand-image img-circle elevation-3 " style="height: 50px">
                        <div>Republic of the Philippines
                        </div>
                        <div>
                            Department of Environment and Natural Resources
                        </div>
                        <div>
                            <strong>PENRO - Occidental Mindoro</strong>
                        </div>
                    </div>
                </div>
                <div class='pt-4'>
                    <h5>TRAVEL ORDER</h5>
                </div>
                <div>
                    No. <span class="text-bold"> {{ $TravelOrdernumber->travelorderid }}</span>
                </div>
            </div>
            <div class="row">
                <div class=" invoice-info mb-2 ml-2 mt-4 col-6">
                    <div class="row mb-2">
                        <div class="">
                            &nbsp;
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="">
                            <strong> Name : </strong>
                        </div>
                        <div class="px-2">
                            {{$Employee->firstname .' '. $Employee->middlename .' '. $Employee->lastname }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="">
                            <strong> Position : </strong>
                        </div>
                        <div class="px-2">
                            {{$Employee->position }}
                        </div>
                    </div>

                </div>

                <div class=" invoice-info mb-2 ml-2 mt-4 col-5">
                    <div class="row mb-2">
                        <div>
                            <strong> Date : </strong>
                        </div>
                        <div class="px-2">
                            {{ $docDate }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div>
                            <strong> Salary (PhP) : </strong>
                        </div>
                        <div class="px-2">

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div>
                            <strong> Div/Sec/Unit : </strong>
                        </div>
                        <div class="px-2">
                            {{$Employee->unit->unit}}

                        </div>
                    </div>
                    <div class="row mb-2">
                        <div>
                            <strong> Official Station : </strong>
                        </div>
                        <div class="px-2">
                            {{$Employee->office->office}}
                        </div>
                    </div>

                </div>

            </div>

            <div class="row">
                <div class=" invoice-info mb-2 ml-2 col-6">
                    <div class="row mb-2">
                        <div class="">
                            <strong> Departure Date : </strong>
                        </div>
                        <div class="px-2">
                            {{$date1 }}
                        </div>
                    </div>
                </div>
                <div class=" invoice-info mb-2 ml-2 col-5">
                    <div class="row mb-2">
                        <div>
                            <strong> Arrival Date : </strong>
                        </div>
                        <div class="px-2">
                            {{$date2}}

                        </div>
                    </div>
                </div>

            </div>


            <div>
                <div class="row mb-2 ml-1">
                    <div class="">
                        <strong> Destination : </strong>
                    </div>
                    <div class="px-2">
                        {{$TravelOrder->destinationoffice}}
                    </div>

                </div>
            </div>
            <div>
                <div class="row mb-2 ml-1">
                    <div class="">
                        <strong> Purpose of Travel : </strong>
                    </div>
                    <div class="px-2">
                        {{$TravelOrder->purpose}}
                    </div>

                </div>
            </div>
            <div>
                <div class="row mb-2 ml-1">
                    <div class="">
                        <strong> Per Diems/Expenses Allowed : </strong>
                    </div>
                    <div class="px-2">
                        {{$TravelOrder->perdime}}
                    </div>

                </div>
            </div>
            <div>
                <div class="row mb-2 ml-1">
                    <div class="">
                        <strong> Appropriations to which travel should be charged : </strong>
                    </div>
                    <div class="px-2">
                        {{$TravelOrder->appropriation }}
                    </div>

                </div>
            </div>
            <div>
                <div class="row mb-2 ml-1">
                    <div class="">
                        <strong> Remarks or Special Instructions : </strong>
                    </div>
                    <div class="px-2">
                        {{$TravelOrder->remarks }}
                    </div>

                </div>
            </div>
            <br>
            <div class="text-center mt-4">
                <div class="mt-2">
                    <h5>CERTIFICATION :</h5>
                </div>

            </div>
            <div class="mb-4">
                <div>
                    This is to certify that the travel is necessary and is connected with the functions of the
                    official/employee of the Div/Sec/Unit.
                </div>
            </div>

            <div class="row mt-4">
                <div class=" invoice-info mb-2 ml-2 col-6">
                    <div class="mb-2 text-center">
                        <div class="">

                        </div>
                        <div class="px-2 ">

                        </div>
                    </div>
                </div>
                <div class=" invoice-info mb-2 ml-2 col-5">
                    <div class="mb-2">
                        <div>
                            <strong> Approved : </strong>
                        </div>
                        <div class="px-2">


                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- @php
                use Illuminate\Support\Facades\Storage;

               
                $sigUrl1 = null;
                if (!empty($approver1Emp?->signature_path)) {
                $p1 = ltrim(str_replace('\\','/',$approver1Emp->signature_path), '/');
                if (Storage::disk('public')->exists($p1)) {
                $sigUrl1 = Storage::url($p1);
                } elseif (file_exists(public_path($p1))) {
                $sigUrl1 = asset($p1); 
                }
                }

               
                $sigUrl2 = null;
                if (!empty($approver2Emp?->signature_path)) {
                $p2 = ltrim(str_replace('\\','/',$approver2Emp->signature_path), '/');
                if (Storage::disk('public')->exists($p2)) {
                $sigUrl2 = Storage::url($p2);
                } elseif (file_exists(public_path($p2))) {
                $sigUrl2 = asset($p2);
                }
                }
                @endphp --}}

                <div class="invoice-info mb-2 ml-2 col-5">
                    <div class="mb-2 text-center">
                        <div class="d-flex align-items-center flex-column">
                            <img src="{{ $approver1Emp->signature_url ?? asset('images/dummySign.png') }}" style="height:50px">
                            <strong class="border-bottom">{{ $approver1Name }}</strong>
                        </div>
                        <div class="px-2"><span>{{ $approver1Pos }}</span></div>
                        {{-- DEBUG: {{ $sigUrl1 }} --}}
                    </div>
                </div>

                <div class="invoice-info mb-2 ml-2 col-6">
                    <div class="mb-2 text-center">
                        <div class="d-flex align-items-center flex-column">
                            <img src="{{ $approver2Emp->signature_url ?? asset('images/dummySign.png') }}" style="height:50px">
                            <strong class="border-bottom">{{ $approver2Name }}</strong>
                        </div>
                        <div class="px-2"><span>{{ $approver2Pos }}</span></div>
                        {{-- DEBUG: {{ $sigUrl2 }} --}}
                    </div>
                </div>
            </div>


            <div class="text-center mt-4">
                <div class="mt-2">
                    <h5>AUTHORIZATION :</h5>
                </div>
            </div>

            <div class="mb-4">
                <div>
                    I hereby authorize the Accountant to deduct the corresponding amount of the unliquidated cash
                    advance from my succeeding salary for my failure to liquidate this travel within the prescribed
                    thirty-day period upon return to my permanent official station pursuant to item 5.1.3 Circular
                    97-002 dated February 10, 1997 and Sec. 16 EO no.248 dated May 29, 1995.
                </div>
            </div>
            <br>
            <br>


            <div class="mt-4">
                <div class="mb-2 text-center">
                    <div>
                        <strong class="border-bottom"> {{$Employee->firstname .' '. $Employee->middlename .' '.
                            $Employee->lastname }} </strong>
                    </div>
                    <div class="px-2">
                        Official/Employee

                    </div>
                </div>

            </div>



        </section>
    </div>

    <!-- Page specific script -->
    <script>
        // window.addEventListener("load", window.print());

        // (function () {
        // function openPrint() { try { window.print(); } catch (e) {} }
        // function goBack() { history.back(); }

      
        // if ('onafterprint' in window) {
        // window.addEventListener('afterprint', goBack);
        // } else {
        
        // const mql = window.matchMedia('print');
        // mql.addListener(function (mq) {
        // if (!mq.matches) goBack();
        // });
        // }

        // window.addEventListener('load', openPrint);
        // })();

        (function () {
        const params = new URLSearchParams(location.search);
        const embedded = params.has('embed');

        // auto-open print dialog
        window.addEventListener('load', () => window.print());

        // kung HINDI embedded (i.e., direct visit), bumalik sa list pagkatapos
        if (!embedded) {
        window.addEventListener('afterprint', () => history.back());
        }
        })();


    </script>
</body>

</html>
