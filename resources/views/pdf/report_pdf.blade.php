<style>
    thead tr th {
        vertical-align: middle;
    }

    .header-table th {
        text-align: center;
        border: 1px solid #777;
    }
</style>
<table>
    <thead>
    <tr>
        <th colspan="6" style="text-align: left;">BÁO CÁO SỔ KHÁM BỆNH CỦA BỆNH NHÂN {{date("d/m/Y", time())}}</th>
    </tr>
    <tr>
        {{-- <th colspan="6" style="text-align: left;">Tên nhân viên: {{$full_name}}</th> --}}
    </tr>
    <tr>
        <th></th>
        <th>TỪ NGÀY</th>
        <th>{{date("d/m/Y", strtotime($from))}}</th>
        <th>ĐẾN NGÀY</th>
        <th>{{date("d/m/Y", strtotime($to))}}</th>
    </tr>
    <tr class="header-table">
        <th colspan="8" style="text-align: center !important;">THÔNG TIN CHI TIẾT</th>
    </tr>
    <tr class="header-table">
        <th>Tên bệnh nhân</th>
        <th>Vai trò</th>
        <th>SĐT</th>
        <th>Địa chỉ email</th>
        <th>Địa chỉ liên lạc</th>
        <th>Ngày sinh</th>
        <th style="text-align: left;">Tài khoản</th>
        <th>Ngày kích hoạt</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($number_HRB))
        @foreach($number_HRB as $item)
            <tr>
                <td style="border: 1px solid #777">{{!empty($item) ? $item['name_doctor'] : ''}}</td>
                <td style="border: 1px solid #777">{{!empty($item) ? $item['role'] : ''}}</td>
                <td style="border: 1px solid #777">{{!empty($item) ? $item['phone_number'] : ''}}</td>
                <td style="border: 1px solid #777">{{!empty($item) ? $item['email'] : ''}}</td>
                <td style="border: 1px solid #777">{{!empty($item) ? $item['address'] : ''}}</td>
                <td style="border: 1px solid #777">{{!empty($item) ? $item['birthday'] : ''}}</td>
                <td style="border: 1px solid #777">{{!empty($item) ? $item['username'] : ''}}</td>
                <td style="border: 1px solid #777">{{!empty($item) ? $item['active'] : ''}}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>