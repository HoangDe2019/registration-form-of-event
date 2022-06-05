<?php
/**
 * Created by PhpStorm.
 * User: Ho Sy Dai
 * Date: 10/25/2018
 * Time: 11:03 AM
 */

$table_validate = [
    "cultures" => [
        "EN" => "The culture",
        "VI" => "Thông tin canh tác",
    ],
    "cultures.fk" => [
        "EN" => "Can not delete [{0}] because using in culture",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách cây canh tác",
    ],
    "cultures.not-exist" => [
        "EN" => "Culture of [{0}] for [{1}] not exist",
        "VI" => "Thông tin canh tác của [{0}] cho [{1}] không tồn tại",
    ],
    "cultures.existed" => [
        "EN" => "Culture of [{0}] with [{1}] existed",
        "VI" => "Thông tin canh tác của [{0}] trong [{1}] đã tồn tại",
    ],
    "cultures.create-success" => [
        "EN" => "Culture of [{0}] has created successful",
        "VI" => "Thông tin canh tác của [{0}] vừa được tạo thành công",
    ],
    "cultures.update-success" => [
        "EN" => "Culture of [{0}] has updated successful",
        "VI" => "Thông tin canh tác của [{0}] vừa chỉnh sửa thành công",
    ],
    "cultures.force-delete-success" => [
        "EN" => "Culture of [{0}] has deleted",
        "VI" => "Bạn vừa xóa thông tin canh tác của [{0}] thành công",
    ],
    "cultures.delete-success" => [
        "EN" => "Culture of [{0}] for [{1}] has deleted",
        "VI" => "Bạn vừa xóa thông tin canh tác của [{0}] cho [{1}] thành công",
    ],
    "cultures.delete-fail-plant-existed" => [
        "EN" => "Can not delete [{0}] because culture existed",
        "VI" => "Không thể xóa thông tin canh tác của [{0}] do nông dân đã canh tác.",
    ],
    "cultures.delete-fail-info-existed" => [
        "EN" => "Can not delete [{0}] because culture existed",
        "VI" => "Không thể xóa [{0}] do nông dân đã sử dụng để canh tác.",
    ],

    # Plants
    "plants.fk" => [
        "EN" => "Can not delete [{0}] because using in plants",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách cây trồng",
    ],
    "plants.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "plants.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "plants.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Users
    "users.fk" => [
        "EN" => "Can not delete [{0}] because using in plants",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách cây trồng",
    ],
    "users.login-invalid" => [
        "EN" => "Phone or password is invalid!",
        "VI" => "Số điện thoại hoặc mật khẩu không đúng!",
    ],
    "users.admin-login-invalid" => [
        "EN" => "User or password is invalid!",
        "VI" => "Tên đăng nhập hoặc mật khẩu không đúng!",
    ],
    "users.admin-login-invalid-device" => [
        "EN" => "User deny login on APP!",
        "VI" => "Tài khoản này không được login trên APP!",
    ],
    "users.user-inactive" => [
        "EN" => "The user is inactive",
        "VI" => "Tài khoản chưa được kích hoạt",
    ],
    "users.login-not-exist" => [
        "EN" => "[{0}] is not exist or not activate.",
        "VI" => "[{0}] chưa được đăng ký hoặc chưa kích hoạt.",
    ],
    "users.login-not-allow" => [
        "EN" => "[{0}] is not allow to access.",
        "VI" => "[{0}] không được phép.",
    ],
    "users.not-exist" => [
        "EN" => "[{0}] not exist",
        "VI" => "[{0}] không tồn tại",
    ],
    "users.existed" => [
        "EN" => "Culture of [{0}] not exist",
        "VI" => "Thông tin canh tác của [{0}] không tồn tại",
    ],
    "users.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "users.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "users.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],
    "users.register-success" => [
        "EN" => "[{0}] has register successful",
        "VI" => "[{0}] đã được đăng ký thành công",
    ],
    "users.active-success" => [
        "EN" => "Account [{0}] has activated successful",
        "VI" => "Tài khoản [{0}] vừa được kích hoạt thành công",
    ],
    "users.inactive-success" => [
        "EN" => "Account [{0}] has inactivated successful",
        "VI" => "Tài khoản [{0}] vừa được vô hiệu hóa thành công",
    ],
    "users.change-password" => [
        "EN" => "Change password successful",
        "VI" => "Thay đổi mật khẩu thành công",
    ],
    "users.reset-password-success" => [
        "EN" => "Reset password successful",
        "VI" => "Khôi phục mật khẩu thành công",
    ],

    "users.check-fail" => [
        "EN" => "Account is not exist or not activate.",
        "VI" => "Tài khoản không tồn tại hoặc chưa được kích hoạt.",
    ],
    # Products
    "products.fk" => [
        "EN" => "Can not delete [{0}] because using in products",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách sản phẩm",
    ],
    "products.import-success" => [
        "EN" => "[{0}] products has imported successful",
        "VI" => "[{0}] sản phẩm vừa được import thành công",
    ],
    "products.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "products.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "products.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa sản phẩm [{0}] thành công",
    ],

    # Roles
    "roles.fk" => [
        "EN" => "Can not delete [{0}] because using in roles",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách các vai trò của ứng dụng",
    ],
    "roles.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "roles.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "roles.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Plant Group
    "plant_groups.fk" => [
        "EN" => "Can not delete [{0}] because using in roles",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách các nhóm cây",
    ],
    "plant_groups.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "plant_groups.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "plant_groups.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Info
    "info.fk" => [
        "EN" => "Can not delete [{0}] because using in info",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "info.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "info.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "info.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Support
    "supports.fk" => [
        "EN" => "Can not delete [{0}] because using in info",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "supports.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "supports.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "supports.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Special
    "specials.fk" => [
        "EN" => "Can not delete [{0}] because using in info",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "specials.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "specials.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "specials.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Contacts
    "contacts.fk" => [
        "EN" => "Can not delete [{0}] because using in plants",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách cây trồng",
    ],
    "contacts.user-create-success" => [
        "EN" => "Thank you for sending {0} about {1} for us.",
        "VI" => "Chân thành cảm ơn bạn đã gửi {0} về {1} tới chúng tôi.",
    ],
    "contacts.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "contacts.update-success" => [
        "EN" => "Contact have updated from [{0}] to [{1}] successful",
        "VI" => "Trạng thái của liên hệ vừa được đổi từ [{0}] sang [{1}]",
    ],
    "contacts.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Notify
    "notifies.fk" => [
        "EN" => "Can not delete [{0}] because using in info",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "notifies.allow-farmer" => [
        "EN" => "Only the farmer can see notification.",
        "VI" => "Tính năng thông báo chỉ được sử dụng cho nông dân.",
    ],
    "notifies.read-success" => [
        "EN" => "You have read notify [{0}].",
        "VI" => "Cảm ơn bạn đã quan tâm tới [{0}]",
    ],
    "notifies.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "notifies.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "notifies.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Diary
    "diaries.fk" => [
        "EN" => "Can not delete [{0}] because using",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "diaries.not-exist" => [
        "EN" => "Diary of [{0}] in [{1}] does not exist",
        "VI" => "Nhật ký canh tác của [{0}] trong [{1}] chưa được tạo",
    ],
    "diaries.create-success" => [
        "EN" => "Diary of [{0}] in [{1}] has created successful",
        "VI" => "Nhật ký canh tác của cây [{0}] trong [{1}] vừa được tạo thành công",
    ],
    "diaries.update-success" => [
        "EN" => "Diary of [{0}] in [{1}] has updated successful",
        "VI" => "Nhật ký canh tác của cây [{0}] trong [{1}] vừa được sửa thành công",
    ],
    "diaries.delete-success" => [
        "EN" => "Diary of [{0}] in [{1}] has deleted successful",
        "VI" => "Nhật ký canh tác của cây [{0}] trong [{1}] vừa được xóa thành công",
    ],

    # NPK Manure
    "npk_manures.not-enough" => [
        "EN" => "Please select full manure",
        "VI" => "Bạn chưa chọn hết phân để trộn",
    ],

    # Plant Bug
    "plant_bugs.fk" => [
        "EN" => "Can not delete [{0}] because using in plant bug",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "plant_bugs.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "plant_bugs.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "plant_bugs.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Product Source
    "product_sources.fk" => [
        "EN" => "Can not delete [{0}] because using in plant bug",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "product_sources.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "product_sources.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "product_sources.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Customers
    "customers" => [
        "EN" => "Customers",
        "VI" => "Khách hàng | Đại lý",
    ],
    "customers.fk" => [
        "EN" => "Can not delete [{0}] because using in customers",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách khách hàng",
    ],
    "customers.login-invalid" => [
        "EN" => "Code or password is invalid!",
        "VI" => "Mã khách hàng hoặc mật khẩu không đúng!",
    ],
    "customers.login-not-exist" => [
        "EN" => "Customer {0} is not exist or not activate.",
        "VI" => "Khách hàng {0} chưa được đăng ký hoặc chưa kích hoạt.",
    ],
    "customers.login-not-allow" => [
        "EN" => "Customer {0} is not allow to access.",
        "VI" => "Khách hàng {0} không được phép truy cập.",
    ],
    "customers.delete-not-allow" => [
        "EN" => "Customer {0} is not allow to delete.",
        "VI" => "Khách hàng {0} không được phép xóa.",
    ],
    "customers.not-exist" => [
        "EN" => "Customer {0} not exist",
        "VI" => "Khách hàng {0} không tồn tại",
    ],
    "customers.existed" => [
        "EN" => "Customer {0} existed",
        "VI" => "Khách hàng [{0}] đã tồn tại",
    ],
    "customers.customer-inactive" => [
        "EN" => "The customer is inactive",
        "VI" => "Tài khoản chưa được kích hoạt",
    ],
    "customers.create-success" => [
        "EN" => "Customer {0} has created successful",
        "VI" => "Khách hàng {0} vừa được tạo thành công",
    ],
    "customers.update-success" => [
        "EN" => "Customer {0} has updated successful",
        "VI" => "Khách hàng {0} vừa được chỉnh sửa thành công",
    ],
    "customers.delete-success" => [
        "EN" => "Customer {0} has deleted",
        "VI" => "Bạn vừa xóa khách hàng {0} thành công",
    ],
    "customers.register-success" => [
        "EN" => "Customer {0} has register successful",
        "VI" => "Khách hàng {0} đã được đăng ký thành công",
    ],
    "customers.active-success" => [
        "EN" => "Account [{0}] has activated successful",
        "VI" => "Khách hàng {0} vừa được kích hoạt thành công",
    ],
    "customers.inactive-success" => [
        "EN" => "Account [{0}] has inactivated successful",
        "VI" => "Khách hàng {0} vừa được vô hiệu hóa thành công",
    ],
    "customers.add-for-seller" => [
        "EN" => "Seller: {0} has added customers successful",
        "VI" => "Đã thêm đại lý cho nhân viên: {0}",
    ],

    # Orders
    "orders.fk" => [
        "EN" => "Can not delete [{0}] because using in orders",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách đơn hàng",
    ],
    "orders.not-exist" => [
        "EN" => "Order #{0} not exist",
        "VI" => "Đơn hàng #{0} không tồn tại",
    ],
    "orders.existed" => [
        "EN" => "Order #{0} does exist",
        "VI" => "Đơn hàng #{0} đã tồn tại",
    ],
    "orders.create-success" => [
        "EN" => "Order #{0} has created successful",
        "VI" => "Đơn hàng #{0} vừa được tạo thành công",
    ],
    "orders.print-success" => [
        "EN" => "Order #{0} has printed successful",
        "VI" => "In đơn hàng #{0} thành công",
    ],
    "orders.update-success" => [
        "EN" => "Order #{0} has updated successful",
        "VI" => "Đơn hàng #{0} vừa được chỉnh sửa thành công",
    ],
    "orders.update-block" => [
        "EN" => "Order #{0} deny update",
        "VI" => "Đơn hàng #{0} không được cập nhật",
    ],
    "orders.delete-success" => [
        "EN" => "Order #{0} has deleted",
        "VI" => "Bạn vừa xóa đơn hàng #{0} thành công",
    ],
    "orders.change-status-block" => [
        "EN" => "Order #{0} can not change.",
        "VI" => "Đơn hàng #{0} không được thay đổi",
    ],
    "orders.change-status-allow" => [
        "EN" => "Order #{0} can only change to statuses {1}",
        "VI" => "Đơn hàng #{0} chỉ được thay đổi thành các trạng thái {1}",
    ],
    "orders.change-status-success" => [
        "EN" => "Order #{0} has register successful",
        "VI" => "Đơn hàng #{0} đã được thay đổi trạng thái thành công",
    ],
    "orders.active-success" => [
        "EN" => "Order #{0} has activated successful",
        "VI" => "Đơn hàng #{0} vừa được kích hoạt thành công",
    ],
    "orders.inactive-success" => [
        "EN" => "Order #{0} has inactivated successful",
        "VI" => "Đơn hàng #{0} vừa được vô hiệu hóa thành công",
    ],

    # Promotions
    "promotions.fk" => [
        "EN" => "Can not delete [{0}] because using in promotions",
        "VI" => "[{0}] không thể xóa do đang sử dụng trong danh sách khuyến mãi",
    ],
    "promotions.not-exist" => [
        "EN" => "Promotion #{0} not exist",
        "VI" => "Khuyến mãi #{0} không tồn tại",
    ],
    "promotions.existed" => [
        "EN" => "Promotion #{0} does exist",
        "VI" => "Khuyến mãi #{0} đã tồn tại",
    ],
    "promotions.create-success" => [
        "EN" => "Promotion #{0} has created successful",
        "VI" => "Khuyến mãi #{0} vừa được tạo thành công",
    ],
    "promotions.update-success" => [
        "EN" => "Promotion #{0} has updated successful",
        "VI" => "Khuyến mãi #{0} vừa được chỉnh sửa thành công",
    ],
    "promotions.delete-success" => [
        "EN" => "Promotion #{0} has deleted",
        "VI" => "Bạn vừa xóa khuyến mãi #{0} thành công",
    ],
    "promotions.change-status-success" => [
        "EN" => "Promotion #{0} has register successful",
        "VI" => "Khuyến mãi #{0} đã được thay đổi trạng thái thành công",
    ],
    "promotions.active-success" => [
        "EN" => "Promotion #{0} has activated successful",
        "VI" => "Khuyến mãi #{0} vừa được kích hoạt thành công",
    ],
    "promotions.inactive-success" => [
        "EN" => "Promotion #{0} has inactivated successful",
        "VI" => "Khuyến mãi #{0} vừa được vô hiệu hóa thành công",
    ],

    # Warehouse
    "warehouses" => [
        "EN" => "Warehouse",
        "VI" => "Kho hàng",
    ],
    "warehouses.fk" => [
        "EN" => "Can not delete [{0}] because using in warehouses",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "warehouses.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "warehouses.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "warehouses.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Unit
    "units" => [
        "EN" => "Unit",
        "VI" => "Đơn vị",
    ],
    "units.fk" => [
        "EN" => "Can not delete [{0}] because using in units",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "units.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "units.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "units.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Unit Convert
    "unit_converts" => [
        "EN" => "Unit Convert",
        "VI" => "Đơn vị chuyển đổi",
    ],
    "unit_converts.fk" => [
        "EN" => "Can not delete [{0}] because using in unit_converts",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "unit_converts.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "Tạo mới đơn vị chuyển đổi thành công",
    ],
    "unit_converts.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "Chỉnh sửa đơn vị chuyển đổi thành công",
    ],
    "unit_converts.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Price
    "prices" => [
        "EN" => "Price Table",
        "VI" => "Bảng giá",
    ],
    "prices.fk" => [
        "EN" => "Can not delete [{0}] because using in prices",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "prices.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "prices.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "prices.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Customer Group
    "customer_groups" => [
        "EN" => "Customer Group",
        "VI" => "Nhóm khách hàng",
    ],
    "customer_groups.fk" => [
        "EN" => "Can not delete [{0}] because using in customer_groups",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "customer_groups.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "customer_groups.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "customer_groups.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Customer Type
    "customer_types" => [
        "EN" => "Customer Group",
        "VI" => "Nhóm khách hàng",
    ],
    "customer_types.fk" => [
        "EN" => "Can not delete [{0}] because using in customer_types",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "customer_types.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "customer_types.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "customer_types.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Cart
    "carts" => [
        "EN" => "Cart",
        "VI" => "Giỏ hàng",
    ],

    # Categories
    "categories" => [
        "EN" => "Category",
        "VI" => "Category",
    ],
    "categories.fk" => [
        "EN" => "Can not delete [{0}] because using in categories",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "categories.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "categories.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "categories.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Salesprice
    "salesprice" => [
        "EN" => "Salesprice",
        "VI" => "salesprice",
    ],
    "salesprice.fk" => [
        "EN" => "Can not delete [{0}] because using in salesprice",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "salesprice.exist-fk" => [
        "EN" => "Cannot create more because it already exists",
        "VI" => "Thông tin sản phẩm này đã bị trùng, vui lòng kiểm tra lại",
    ],
    "salesprice.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "salesprice.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "salesprice.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Invoice
    "invoices" => [
        "EN" => "Invoice",
        "VI" => "Phiếu xuất/nhập kho",
    ],
    "invoices.fk" => [
        "EN" => "Can not delete [{0}] because using in units",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "invoices.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "invoices.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "invoices.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],
    # Master Data Type
    "master_data_type" => [
        "EN" => " Master Data Type",
        "VI" => " Kiểu dữ liệu chủ",
    ],
    "master_data_type.fk" => [
        "EN" => "Can not delete [{0}] because using in master_data_type",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "master_data_type.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "master_data_type.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "master_data_type.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],
    # Master Data
    "master_data" => [
        "EN" => " Master Data ",
        "VI" => "Dữ liệu chủ",
    ],
    "master_data.fk" => [
        "EN" => "Can not delete [{0}] because using in master_data",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "master_data.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "master_data.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "master_data.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Issue
    "task.exist-fk" => [
        "EN" => "Cannot create more because it already exists",
        "VI" => "Thông tin [{0}] đã bị trùng, vui lòng kiểm tra lại",
    ],
    "task.fk" => [
        "EN" => "Can not delete [{0}] because using in master_data",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "task.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "task.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "task.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Categpry Issue
    "category_task.fk" => [
        "EN" => "Can not delete [{0}] because using in master_data",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "category_task.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "category_task.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "category_task.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # DepartMenrts
    "department_data" => [
        "EN" => " Master Data ",
        "VI" => "Dữ liệu chủ",
    ],
    "department.fk" => [
        "EN" => "Can not delete [{0}] because using in master_data",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "department.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "department.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "department.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Folders
    "folders.fk" => [
        "EN" => "Can not delete [{0}] because using in master_data",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "folders.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "folders.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "folders.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Discuss
    "discuss.fk" => [
        "EN" => "Can not delete [{0}] because using in master_data",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "discuss.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "discuss.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "discuss.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ],

    # Permission
    "permission.fk" => [
        "EN" => "Can not delete [{0}] because using in master_data",
        "VI" => "[{0}] không thể xóa do đang sử dụng",
    ],
    "permission.create-success" => [
        "EN" => "[{0}] has created successful",
        "VI" => "[{0}] vừa được tạo thành công",
    ],
    "permission.update-success" => [
        "EN" => "[{0}] has updated successful",
        "VI" => "[{0}] vừa được chỉnh sửa thành công",
    ],
    "permission.delete-success" => [
        "EN" => "[{0}] has deleted",
        "VI" => "Bạn vừa xóa [{0}] thành công",
    ]
];
