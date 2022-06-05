<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <title>Toa thuốc chỉ định</title>
</head>
<body>
<style type="text/css">
    #invoice {
        padding: 30px;
    }

    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
    }

    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
    }

    .invoice .company-details {
        text-align: right
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .contacts {
        margin-bottom: 20px
    }

    .invoice .invoice-to {
        text-align: left
    }

    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .invoice-details {
        text-align: right
    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -100px;
        font-size: 2em;
        margin-bottom: 50px
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }

    .invoice main .notices .notice {
        font-size: 1.2em
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }

    .invoice table td, .invoice table th {
        padding: 15px;
        background: #eee;
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        white-space: nowrap;
        font-weight: 400;
        font-size: 16px
    }

    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        color: #3989c6;
        font-size: 1.2em
    }

    .invoice table .qty, .invoice table .total, .invoice table .unit {
        text-align: right;
        font-size: 1.2em
    }

    .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #3989c6
    }

    .invoice table .unit {
        background: #ddd
    }

    .invoice table .total {
        background: #3989c6;
        color: #fff
    }

    .invoice table tbody tr:last-child td {
        border: none
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.2em;
        border-top: 1px solid #aaa
    }

    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        color: #3989c6;
        font-size: 1.4em;
        border-top: 1px solid #3989c6
    }

    .invoice table tfoot tr td:first-child {
        border: none
    }

    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
    }

    @media print {
        .invoice {
            font-size: 11px !important;
            overflow: hidden !important
        }

        .invoice footer {
            position: absolute;
            bottom: 10px;
            page-break-after: always
        }

        .invoice > div:last-child {
            page-break-before: always
        }
    }
</style>


<!------ Include the above in your HEAD tag ---------->

<!--Author      : @arboshiki-->

<div id="invoice">

    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div><h5 class="invoice-id">Mẫu số:</h5>
                        <b>
                            <div style='text-align: left; max-width: 400px'>
                                <!-- insert your custom barcode setting your data in the GET parameter "data" -->
                                <img style="max-width:400px; max-height: 60px; display: block; position: relative;" alt='Barcode Generator TEC-IT'
                                     src='https://barcode.tec-it.com/barcode.ashx?data={{$precident_code}}&code=&multiplebarcodes=true&translate-esc=true&unit=Fit&dpi=96&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&codepage=Default&qunit=Mm&quiet=0&hidehrt=False'/>
                            </div>
                        </b>
                    </div>
                    <div class="col-md-6 col company-details">
                        <a target="_blank" href="https://lobianijs.com">
                            <img src="data:image/png;base64,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"
                                 style="width: 80px; height:80px;" data-holder-rendered="true"/>
                        </a>
                        <h2 class="name">
                            <a target="_blank" href="https://lobianijs.com">
                                Trường Đại Học Cần Thơ
                            </a>
                        </h2>
                        <div><span class="fa fa-address"><strong>Địa chỉ: </strong></span>Khu II đường 3/2, P.Xuân Khánh, Q.Ninh Kiều, Cần Thơ
                        </div>
                        <div><strong>SĐT liên hệ: </strong>(+84)1230 456-789</div>
                        <div><strong>Emai: </strong>thaob1706869@student.ctu.edu.vn</div>
                        <div>thaob1706869@student.ctu.edu.vn</div>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light"><strong>Mã SKB:</strong>{{$precident_health_id}}</div>
                        <div class="text-gray-light"><strong>Bệnh nhân:</strong> <i>{{$patient_name}}</i></div>
                        <div class="address"><strong>Địa chỉ:</strong> <i>{{$patient_address}}</i></div>
                        <div class="address"><strong>Giới tính:</strong> <i>{{$patient_genre}}</i> -
                            <strong>Tuổi:</strong> <i>{{$patient_age}}</i></div>
                        <div class="email"><strong>Số điện thoại:</strong> <i>{{$patient_phone}}</i></div>
                        <div class="email"><strong>Triệu chứng:</strong> <i>{{$symptom}}</i></div>
                    </div>
                    <div class="col invoice-details">
                    </div>
                </div>
                <h5>Kết quả chuẩn đoán bệnh</h5>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="text-center"><strong>Mã bệnh</strong></th>
                        <th class="text-center"><strong>Loại bệnh</strong></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($diseases as $itemDisease)
                        <tr>
                            <td class="text-center">{{$itemDisease['diseases_code']}}</td>
                            <td class="text-center">{{$itemDisease['diseases_name']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <h5>Kết quả xét nghiệm</h5>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="text-center">Hình thức xét nghiệm</th>
                        <th class="text-center">Kết quả xét nghiệm</th>
                        <th class="text-center">Hình ảnh</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($test_results as $itemTestResult)
                        @if(!empty($itemTestResult))
                            <tr>
                                <td class="text-center">{{$itemTestResult['name_analysis']}}</td>
                                <td class="text-center">{{$itemTestResult['result']}}</td>
                                <td class="text-center"><img src="{{$itemTestResult['img_result']}}"
                                                             style="width:200px; height: 200px;" /></td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>

                <h5>Chi tiết toa thuốc</h5>
                <table border="0" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="text-center">Tên thuốc</th>
                        <th class="text-center">Đơn vị</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Cách dùng</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($medicines as $itemMedicine)
                        <tr>
                            <td class="text-center">{{$itemMedicine['medicine_name']}}</td>
                            <td class="text-center">{{$itemMedicine['medicine_unit']}}</td>
                            <td class="text-center">{{$itemMedicine['medicine_numbers']}}</td>
                            <td class="text-center">{{$itemMedicine['medicine_use']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="thanks"></div>
                <div class="notices">
                    <div>Chú ý:</div>
                    <div class="notice">Nhớ uống thuốc đều đặn <i>{{$follow_up}}</i></div>
                </div>
                <div class="col invoice-details">
                    <div class="date"><i>Ngày {{date('d', strtotime($date))}} tháng {{date('m', strtotime($date))}}
                            năm {{date('Y', strtotime($date))}}</i></div>

                    <strong>Bác sĩ khám bệnh</strong><br><br><br><br><br><br><br><i>{{$doctor_name}}</i>
                </div>
            </main>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>

<script src="JsBarcode.all.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $('#printInvoice').click(function () {
        Popup($('.invoice')[0].outerHTML);

        function Popup(data) {
            window.print();
            return true;
        }
    });

</script>
<script type="text/javascript">
    //JsBarcode("#barcode", {{$precident_code}});
</script>

</body>
</html>