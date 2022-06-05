<?php
/**
 * Created by PhpStorm.
 * User: Ho Sy Dai
 * Date: 10/25/2018
 * Time: 11:03 AM
 */

$message = [
    "required" => [
        "EN" => "The {0} is required.",
        "VI" => "{0} không được để trống.",
    ],
    "numeric" => [
        "EN" => "The {0} must be number",
        "VI" => "{0} phải là kiểu số.",
    ],


    //medicine_id
    "medicine_id" => [
        "EN" => "The {0} existed.",
        "VI" => "Loại thuốc này không tồn tại trong hệ thống kho thuốc! =>",
    ],
    "unique" => [
        "EN" => "The {0} existed.",
        "VI" => "{0} đã tồn tại trong hệ thống.",
    ],
    "path" => [
        "EN" => "The {0} path is not correct.",
        "VI" => "{0} đường dẫn không đúng.",
    ],
    "digits_between" => [
        "EN" => "The {0} must be least :min or max :max digits.",
        "VI" => "{0} phải lớn hơn :min ký tự hoặc ít hơn :max ký tự.",
    ],
    "not_in" => [
        "EN" => "The {0} is invalid.",
        "VI" => "{0} không đúng.",
    ],
    'accepted' => [
        "EN" => 'The {0} must be accepted.',
        "VI" => "{0} phải được chấp nhận."
    ],
    'active_url' => [
        "EN" => 'The {0} is not a valid URL.',
        "VI" => "{0} là một URL sai."
    ],
    'after' => [
        "EN" => 'The {0} must be a date after :date.',
        "VI" => "{0} phải là một ngày sau ngày :date."
    ],
    'after_or_equal' => [
        "EN" => 'The {0} must be a date after or equal to :date.',
        "VI" => "{0} phải là một ngày sau hoặc bằng ngày :date."
    ],
    'alpha' => [
        "EN" => 'The {0} may only contain letters.',
        "VI" => "{0} có thể chỉ chứa ký tự chữ."
    ],
    'alpha_dash' => [
        "EN" => 'The {0} may only contain letters, numbers, and dashes.',
        "VI" => "{0} phải chứa chữ thường, số và dấu gạch ngang."
    ],
    'alpha_num' => [
        "EN" => 'The {0} may only contain letters and numbers.',
        "VI" => "{0} phải chứa chữ thường và chữ số."
    ],
    'array' => [
        "EN" => 'The {0} must be an array.',
        "VI" => "{0} phải là mảng."
    ],
    'before' => [
        "EN" => 'The {0} must be a date before :date.',
        "VI" => "{0} phải là ngày trước ngày :date."
    ],
    'before_or_equal' => [
        "EN" => 'The {0} must be a date before or equal to :date.',
        "VI" => "{0} phải là ngày trước hoặc bằng ngày :date."
    ],
    'max' => [
        "EN" => 'The {0} field must be smaller than :max.',
        "VI" => "{0} tối đa là  10 ký tự."
    ],
    'boolean' => [
        "EN" => 'The {0} field must be true or false.',
        "VI" => "{0} phải là true hoặc false."
    ],
    'confirmed' => [
        "EN" => 'The {0} confirmation does not match.',
        "VI" => "Xác nhận {0} không giống nhau."
    ],
    'date' => [
        "EN" => 'The {0} is not a valid date.',
        "VI" => "{0} là một ngày không đúng."
    ],
    'date_format' => [
        "EN" => 'The {0} does not match the format :format.',
        "VI" => "{0} không đúng với định dạng :format."
    ],
    'different' => [
        "EN" => 'The {0} and :other must be different.',
        "VI" => "{0} và :other phải khác nhau."
    ],
    'digits' => [
        "EN" => 'The {0} must be :digits digits.',
        "VI" => "{0} phải là ký tự :digits."
    ],
    'dimensions' => [
        "EN" => 'The {0} has invalid image dimensions.',
        "VI" => "{0} có kích thước hình ảnh sai."
    ],
    'distinct' => [
        "EN" => 'The {0} field has a duplicate value.',
        "VI" => "{0} có dữ liệu trùng nhau."
    ],
    'email' => [
        "EN" => 'The Email must be a valid email address.',
        "VI" => "Email phải là một email đúng."
    ],
    'exists' => [
        "EN" => 'The selected {0} is invalid.',
        "VI" => "{0} không đúng."
    ],
    'file' => [
        "EN" => 'The {0} must be a file.',
        "VI" => "{0} phải là kiểu tập tin."
    ],
    'filled' => [
        "EN" => 'The {0} field is required.',
        "VI" => "{0} phải bắt buộc."
    ],
    'image' => [
        "EN" => 'The {0} must be an image.',
        "VI" => "{0} phải là một hình ảnh."
    ],
    'in' => [
        "EN" => 'The selected {0} is invalid.',
        "VI" => "{0} không đúng."
    ],
    'in_array' => [
        "EN" => 'The {0} field does not exist in :other.',
        "VI" => "{0} không tồn tại ở trong :other."
    ],
    'integer' => [
        "EN" => 'The {0} must be an integer.',
        "VI" => "{0} phải là số nguyên."
    ],
    'ip' => [
        "EN" => 'The {0} must be a valid IP address.',
        "VI" => "{0} phải là địa chỉ IP đúng."
    ],
    'json' => [
        "EN" => 'The {0} must be a valid JSON string.',
        "VI" => "{0} phải là chuỗi JSON đúng."
    ],
//    'max' => [
//        'numeric' => 'The {0} may not be greater than :max.',
//        'file' => 'The {0} may not be greater than :max kilobytes.',
//        'string' => 'The {0} may not be greater than :max characters.',
//        'array' => 'The {0} may not have more than :max items.',
//    ],
    'mimes' => [
        "EN" => 'The {0} must be a file of type: :values.',
        "VI" => "{0} phải là một tập tin hoặc thuộc loại: :values."
    ],
    'mimetypes' => [
        "EN" => 'The {0} must be a file of type: :values.',
        "VI" => "{0} phải là một tập tin hoặc thuộc loại: :values."
    ],
//    'min' => [
//        'numeric' => 'The {0} must be at least :min.',
//        'file' => 'The {0} must be at least :min kilobytes.',
//        'string' => 'The {0} must be at least :min characters.',
//        'array' => 'The {0} must have at least :min items.',
//    ],

    'present' => [
        "EN" => 'The {0} field must be present.',
        "VI" => "{0} phải có sẵn."
    ],
    'regex' => [
        "EN" => 'The {0} format is invalid.',
        "VI" => "Định dạng của {0} không đúng."
    ],

    'required_if' => [
        "EN" => 'The {0} field is required when :other is :value.',
        "VI" => "{0} là bắt buộc khi :other là :value."
    ],
    'required_unless' => [
        "EN" => 'The {0} field is required unless :other is in :values.',
        "VI" => "{0} là bắt buộc trừ khi :other là :values."
    ],
    'required_with' => [
        "EN" => 'The {0} field is required when :values is present.',
        "VI" => "{0} là bắt buộc khi :values có sẵn."
    ],
    'required_with_all' => [
        "EN" => 'The {0} field is required when :values is present.',
        "VI" => "{0} là bắt buộc khi :values có sẵn."
    ],
    'required_without' => [
        "EN" => 'The {0} field is required when :values is not present.',
        "VI" => "{0} là bắt buộc khi :values không có sẵn."
    ],
    'required_without_all' => [
        "EN" => 'The {0} field is required when none of :values are present.',
        "VI" => "{0} là bắt buộc khi không có :values có sẵn."
    ],
    'same' => [
        "EN" => 'The {0} and :other must match.',
        "VI" => "{0} và :other phải tương xứng nhau."
    ],
//    'size' => [
//        'numeric' => 'The {0} must be :size.',
//        'file' => 'The {0} must be :size kilobytes.',
//        'string' => 'The {0} must be :size characters.',
//        'array' => 'The {0} must contain :size items.',
//    ],
    'string' => [
        "EN" => 'The {0} must be a string.',
        "VI" => "{0} phải là một chuỗi."
    ],
    'timezone' => [
        "EN" => 'The {0} must be a valid zone.',
        "VI" => "{0} phải là một vùng đúng."
    ],

    'prescription_id' => [
        "EN" => 'Bill is not correct.',
        "VI" => "Toa thuốc không tồn tại-"
    ],

    'health_record_book_id' => [
        "EN" => 'The {0} does not exist.',
        "VI" => "{0} không tồn tại. "
    ],
    'diseases_id' => [
        "EN" => "Department",
        "VI" => "Loại bệnh này không tồn tại",
    ],
    'time' => [
        "EN" => "time ",
        "VI" => "lịch hẹn",
    ],
    'uploaded' => [
        "EN" => 'The {0} failed to upload.',
        "VI" => "{0} lỗi upload."
    ],
    'url' => [
        "EN" => 'The {0} format is invalid.',
        "VI" => "Định dạng {0} không đúng."
    ],

    'week_error' => [
        "EN" => 'The {0} error.',
        "VI" => "Thời gian làm việc trong lịch làm viêc được phân công trong tuần đó có id {0} đó không hơp lệ với ngày bắt đầu và kết thúc."
    ],
    
    'user_doctor' => [
        "EN" => 'The {0} error.',
        "VI" => "Chỉ có thể sắp lịch làm việc cho bác sĩ ngoại trừ id {0}."
    ],
    'user_doctor_schedule' => [
        "EN" => 'The {0} error.',
        "VI" => "bác sĩ {0} đã có ngày làm việc trong tuần này!Vui lòng sắp lịch hôm khác."
    ],
    'booking_time' => [
        "EN" => 'The {0} error.',
        "VI" => "Hiện tại khung giờ từ {0} đến {1} đã có bệnh nhân khác đã đặt giờ này trong ngày hôm nay. Xin vui lòng chọn khung giờ khác!"
    ],

    'booking_number_count' => [
        "EN" => 'The time {0} to {1} error.',
        "VI" => "Hiện tại khung giờ đặt từ {0} đến {1} đã đầy, vậy chỉ có thể đặt tối đa 5 bệnh nhân. Xin vui lòng bệnh nhân chọn khung giờ khác!"
    ],
    'medical_history_error' => [
        "EN" => 'The {0} error.',
        "VI" => "Hiện tại lần khám {0} này đã tồn tại. Xin vui lòng thử lại!"
    ],
    'book_check' => [
        "EN" => 'The {0} error.',
        "VI" => "Thời gian đặt lịch hẹn {0} phải nhỏ hơn {1}. Xin vui lòng chọn lại khung giờ bắt đầu cho hợp lý!"
    ],
    'book_checked_time_choice' => [
        "EN" => 'The time error.',
        "VI" => "Thời gian đặt lịch hẹn {0} đến {1} phải nhỏ hơn khung giờ mà bệnh nhân khác đã được chọn {2} đến {3}. Xin vui lòng chọn lại khung giờ khác cho hợp lý!"
    ],
    'check_time_create'=>[
        "EN" => 'The time error.',
        "VI" => "Thời gian trong ngay {0} phải nhỏ hơn {1}. Xin vui lòng chọn lại khung giờ khác cho hợp lý!"
    ],
    'check_time_create_of_day_validity_start'=>[
        "EN" => 'The time error.',
        "VI" => "Thời gian bắt đầu {0} khi nhập vào không được trùng với khung thời gian bắt đầu {1} đến {2} đã có trong DB. Xin vui nhập lại!"
    ],
    'check_time_create_of_day_validity_end'=>[
        "EN" => 'The time error.',
        "VI" => "Thời gian kết thúc {0} khi nhập vào không được trùng với khung thời gian bắt đầu {1} đến {2} đã có trong DB. Xin vui nhập lại!"
    ],
    'check_time_MorningOrAfternoon_Start' => [
        "EN" => 'The time error.',
        "VI" => "Thời gian bắt đầu {0} khi nhập vào không nằm trong khung giờ làm việc buổi sáng bắt đầu từ lúc 06:00 đến 11:00 và buổi chiều từ lúc 13:30 đến 16:30. Xin vui nhập lại!"
    ],
    'check_time_MorningOrAfternoon_End' => [
        "EN" => 'The time error.',
        "VI" => "Thời gian kết thúc {0} khi nhập vào không nằm trong khung giờ làm việc buổi sáng bắt đầu từ lúc 06:00 đến 11:00 và buổi chiều từ lúc 13:30 đến 16:30. Xin vui nhập lại!"
    ],
    'booking_check_sessions' => [
        "EN" => 'The time {0} to {1} error {2}.',
        "VI" => "Hiện tại khung giờ đặt từ {0} đến {1} không đúng với giờ làm việc của bác sĩ vào buổi {2}, Xin vui lòng bệnh nhân chọn khung giờ đúng với giờ làm việc của bác sĩ!"
    ],
    'booking_check_userLogin_patient' => [
        "EN" => 'The user with role {0} do not support',
        "VI" => "Hiện tại bạn không được phép đặt lịch hẹn với người dùng là {0}. Vì vậy chỉ có bênh nhân mới được phép đặt lịch khám bệnh với bác sĩ. Mọi thắc mắc xin vui lòng xin vui lòng liên hệ người quản trị để được hỗ trợ!. Xin cảm ơn"
    ],
];