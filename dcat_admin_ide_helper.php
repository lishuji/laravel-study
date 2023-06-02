<?php

/**
 * A helper file for Dcat Admin, to provide autocomplete information to your IDE
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author jqh <841324345@qq.com>
 */
namespace Dcat\Admin {
    use Illuminate\Support\Collection;

    /**
     * @property Grid\Column|Collection name
     * @property Grid\Column|Collection created_at
     * @property Grid\Column|Collection detail
     * @property Grid\Column|Collection id
     * @property Grid\Column|Collection type
     * @property Grid\Column|Collection updated_at
     * @property Grid\Column|Collection version
     * @property Grid\Column|Collection is_enabled
     * @property Grid\Column|Collection extension
     * @property Grid\Column|Collection icon
     * @property Grid\Column|Collection order
     * @property Grid\Column|Collection parent_id
     * @property Grid\Column|Collection uri
     * @property Grid\Column|Collection menu_id
     * @property Grid\Column|Collection permission_id
     * @property Grid\Column|Collection http_method
     * @property Grid\Column|Collection http_path
     * @property Grid\Column|Collection slug
     * @property Grid\Column|Collection role_id
     * @property Grid\Column|Collection user_id
     * @property Grid\Column|Collection value
     * @property Grid\Column|Collection avatar
     * @property Grid\Column|Collection password
     * @property Grid\Column|Collection remember_token
     * @property Grid\Column|Collection username
     * @property Grid\Column|Collection deleted_at
     * @property Grid\Column|Collection level
     * @property Grid\Column|Collection status
     * @property Grid\Column|Collection connection
     * @property Grid\Column|Collection exception
     * @property Grid\Column|Collection failed_at
     * @property Grid\Column|Collection payload
     * @property Grid\Column|Collection queue
     * @property Grid\Column|Collection uuid
     * @property Grid\Column|Collection category_id
     * @property Grid\Column|Collection good_id
     * @property Grid\Column|Collection code
     * @property Grid\Column|Collection image
     * @property Grid\Column|Collection price
     * @property Grid\Column|Collection stock
     * @property Grid\Column|Collection email
     * @property Grid\Column|Collection token
     * @property Grid\Column|Collection abilities
     * @property Grid\Column|Collection last_used_at
     * @property Grid\Column|Collection tokenable_id
     * @property Grid\Column|Collection tokenable_type
     * @property Grid\Column|Collection community_code
     * @property Grid\Column|Collection desc
     * @property Grid\Column|Collection house
     * @property Grid\Column|Collection lift
     * @property Grid\Column|Collection address
     * @property Grid\Column|Collection area
     * @property Grid\Column|Collection developer
     * @property Grid\Column|Collection estate
     * @property Grid\Column|Collection greening_rate
     * @property Grid\Column|Collection introduction
     * @property Grid\Column|Collection thumb
     * @property Grid\Column|Collection total_building
     * @property Grid\Column|Collection total_owner
     * @property Grid\Column|Collection building_code
     * @property Grid\Column|Collection enter_time
     * @property Grid\Column|Collection floor
     * @property Grid\Column|Collection owner_name
     * @property Grid\Column|Collection owner_tel
     * @property Grid\Column|Collection rooms
     * @property Grid\Column|Collection unit
     * @property Grid\Column|Collection email_verified_at
     *
     * @method Grid\Column|Collection name(string $label = null)
     * @method Grid\Column|Collection created_at(string $label = null)
     * @method Grid\Column|Collection detail(string $label = null)
     * @method Grid\Column|Collection id(string $label = null)
     * @method Grid\Column|Collection type(string $label = null)
     * @method Grid\Column|Collection updated_at(string $label = null)
     * @method Grid\Column|Collection version(string $label = null)
     * @method Grid\Column|Collection is_enabled(string $label = null)
     * @method Grid\Column|Collection extension(string $label = null)
     * @method Grid\Column|Collection icon(string $label = null)
     * @method Grid\Column|Collection order(string $label = null)
     * @method Grid\Column|Collection parent_id(string $label = null)
     * @method Grid\Column|Collection uri(string $label = null)
     * @method Grid\Column|Collection menu_id(string $label = null)
     * @method Grid\Column|Collection permission_id(string $label = null)
     * @method Grid\Column|Collection http_method(string $label = null)
     * @method Grid\Column|Collection http_path(string $label = null)
     * @method Grid\Column|Collection slug(string $label = null)
     * @method Grid\Column|Collection role_id(string $label = null)
     * @method Grid\Column|Collection user_id(string $label = null)
     * @method Grid\Column|Collection value(string $label = null)
     * @method Grid\Column|Collection avatar(string $label = null)
     * @method Grid\Column|Collection password(string $label = null)
     * @method Grid\Column|Collection remember_token(string $label = null)
     * @method Grid\Column|Collection username(string $label = null)
     * @method Grid\Column|Collection deleted_at(string $label = null)
     * @method Grid\Column|Collection level(string $label = null)
     * @method Grid\Column|Collection status(string $label = null)
     * @method Grid\Column|Collection connection(string $label = null)
     * @method Grid\Column|Collection exception(string $label = null)
     * @method Grid\Column|Collection failed_at(string $label = null)
     * @method Grid\Column|Collection payload(string $label = null)
     * @method Grid\Column|Collection queue(string $label = null)
     * @method Grid\Column|Collection uuid(string $label = null)
     * @method Grid\Column|Collection category_id(string $label = null)
     * @method Grid\Column|Collection good_id(string $label = null)
     * @method Grid\Column|Collection code(string $label = null)
     * @method Grid\Column|Collection image(string $label = null)
     * @method Grid\Column|Collection price(string $label = null)
     * @method Grid\Column|Collection stock(string $label = null)
     * @method Grid\Column|Collection email(string $label = null)
     * @method Grid\Column|Collection token(string $label = null)
     * @method Grid\Column|Collection abilities(string $label = null)
     * @method Grid\Column|Collection last_used_at(string $label = null)
     * @method Grid\Column|Collection tokenable_id(string $label = null)
     * @method Grid\Column|Collection tokenable_type(string $label = null)
     * @method Grid\Column|Collection community_code(string $label = null)
     * @method Grid\Column|Collection desc(string $label = null)
     * @method Grid\Column|Collection house(string $label = null)
     * @method Grid\Column|Collection lift(string $label = null)
     * @method Grid\Column|Collection address(string $label = null)
     * @method Grid\Column|Collection area(string $label = null)
     * @method Grid\Column|Collection developer(string $label = null)
     * @method Grid\Column|Collection estate(string $label = null)
     * @method Grid\Column|Collection greening_rate(string $label = null)
     * @method Grid\Column|Collection introduction(string $label = null)
     * @method Grid\Column|Collection thumb(string $label = null)
     * @method Grid\Column|Collection total_building(string $label = null)
     * @method Grid\Column|Collection total_owner(string $label = null)
     * @method Grid\Column|Collection building_code(string $label = null)
     * @method Grid\Column|Collection enter_time(string $label = null)
     * @method Grid\Column|Collection floor(string $label = null)
     * @method Grid\Column|Collection owner_name(string $label = null)
     * @method Grid\Column|Collection owner_tel(string $label = null)
     * @method Grid\Column|Collection rooms(string $label = null)
     * @method Grid\Column|Collection unit(string $label = null)
     * @method Grid\Column|Collection email_verified_at(string $label = null)
     */
    class Grid {}

    class MiniGrid extends Grid {}

    /**
     * @property Show\Field|Collection name
     * @property Show\Field|Collection created_at
     * @property Show\Field|Collection detail
     * @property Show\Field|Collection id
     * @property Show\Field|Collection type
     * @property Show\Field|Collection updated_at
     * @property Show\Field|Collection version
     * @property Show\Field|Collection is_enabled
     * @property Show\Field|Collection extension
     * @property Show\Field|Collection icon
     * @property Show\Field|Collection order
     * @property Show\Field|Collection parent_id
     * @property Show\Field|Collection uri
     * @property Show\Field|Collection menu_id
     * @property Show\Field|Collection permission_id
     * @property Show\Field|Collection http_method
     * @property Show\Field|Collection http_path
     * @property Show\Field|Collection slug
     * @property Show\Field|Collection role_id
     * @property Show\Field|Collection user_id
     * @property Show\Field|Collection value
     * @property Show\Field|Collection avatar
     * @property Show\Field|Collection password
     * @property Show\Field|Collection remember_token
     * @property Show\Field|Collection username
     * @property Show\Field|Collection deleted_at
     * @property Show\Field|Collection level
     * @property Show\Field|Collection status
     * @property Show\Field|Collection connection
     * @property Show\Field|Collection exception
     * @property Show\Field|Collection failed_at
     * @property Show\Field|Collection payload
     * @property Show\Field|Collection queue
     * @property Show\Field|Collection uuid
     * @property Show\Field|Collection category_id
     * @property Show\Field|Collection good_id
     * @property Show\Field|Collection code
     * @property Show\Field|Collection image
     * @property Show\Field|Collection price
     * @property Show\Field|Collection stock
     * @property Show\Field|Collection email
     * @property Show\Field|Collection token
     * @property Show\Field|Collection abilities
     * @property Show\Field|Collection last_used_at
     * @property Show\Field|Collection tokenable_id
     * @property Show\Field|Collection tokenable_type
     * @property Show\Field|Collection community_code
     * @property Show\Field|Collection desc
     * @property Show\Field|Collection house
     * @property Show\Field|Collection lift
     * @property Show\Field|Collection address
     * @property Show\Field|Collection area
     * @property Show\Field|Collection developer
     * @property Show\Field|Collection estate
     * @property Show\Field|Collection greening_rate
     * @property Show\Field|Collection introduction
     * @property Show\Field|Collection thumb
     * @property Show\Field|Collection total_building
     * @property Show\Field|Collection total_owner
     * @property Show\Field|Collection building_code
     * @property Show\Field|Collection enter_time
     * @property Show\Field|Collection floor
     * @property Show\Field|Collection owner_name
     * @property Show\Field|Collection owner_tel
     * @property Show\Field|Collection rooms
     * @property Show\Field|Collection unit
     * @property Show\Field|Collection email_verified_at
     *
     * @method Show\Field|Collection name(string $label = null)
     * @method Show\Field|Collection created_at(string $label = null)
     * @method Show\Field|Collection detail(string $label = null)
     * @method Show\Field|Collection id(string $label = null)
     * @method Show\Field|Collection type(string $label = null)
     * @method Show\Field|Collection updated_at(string $label = null)
     * @method Show\Field|Collection version(string $label = null)
     * @method Show\Field|Collection is_enabled(string $label = null)
     * @method Show\Field|Collection extension(string $label = null)
     * @method Show\Field|Collection icon(string $label = null)
     * @method Show\Field|Collection order(string $label = null)
     * @method Show\Field|Collection parent_id(string $label = null)
     * @method Show\Field|Collection uri(string $label = null)
     * @method Show\Field|Collection menu_id(string $label = null)
     * @method Show\Field|Collection permission_id(string $label = null)
     * @method Show\Field|Collection http_method(string $label = null)
     * @method Show\Field|Collection http_path(string $label = null)
     * @method Show\Field|Collection slug(string $label = null)
     * @method Show\Field|Collection role_id(string $label = null)
     * @method Show\Field|Collection user_id(string $label = null)
     * @method Show\Field|Collection value(string $label = null)
     * @method Show\Field|Collection avatar(string $label = null)
     * @method Show\Field|Collection password(string $label = null)
     * @method Show\Field|Collection remember_token(string $label = null)
     * @method Show\Field|Collection username(string $label = null)
     * @method Show\Field|Collection deleted_at(string $label = null)
     * @method Show\Field|Collection level(string $label = null)
     * @method Show\Field|Collection status(string $label = null)
     * @method Show\Field|Collection connection(string $label = null)
     * @method Show\Field|Collection exception(string $label = null)
     * @method Show\Field|Collection failed_at(string $label = null)
     * @method Show\Field|Collection payload(string $label = null)
     * @method Show\Field|Collection queue(string $label = null)
     * @method Show\Field|Collection uuid(string $label = null)
     * @method Show\Field|Collection category_id(string $label = null)
     * @method Show\Field|Collection good_id(string $label = null)
     * @method Show\Field|Collection code(string $label = null)
     * @method Show\Field|Collection image(string $label = null)
     * @method Show\Field|Collection price(string $label = null)
     * @method Show\Field|Collection stock(string $label = null)
     * @method Show\Field|Collection email(string $label = null)
     * @method Show\Field|Collection token(string $label = null)
     * @method Show\Field|Collection abilities(string $label = null)
     * @method Show\Field|Collection last_used_at(string $label = null)
     * @method Show\Field|Collection tokenable_id(string $label = null)
     * @method Show\Field|Collection tokenable_type(string $label = null)
     * @method Show\Field|Collection community_code(string $label = null)
     * @method Show\Field|Collection desc(string $label = null)
     * @method Show\Field|Collection house(string $label = null)
     * @method Show\Field|Collection lift(string $label = null)
     * @method Show\Field|Collection address(string $label = null)
     * @method Show\Field|Collection area(string $label = null)
     * @method Show\Field|Collection developer(string $label = null)
     * @method Show\Field|Collection estate(string $label = null)
     * @method Show\Field|Collection greening_rate(string $label = null)
     * @method Show\Field|Collection introduction(string $label = null)
     * @method Show\Field|Collection thumb(string $label = null)
     * @method Show\Field|Collection total_building(string $label = null)
     * @method Show\Field|Collection total_owner(string $label = null)
     * @method Show\Field|Collection building_code(string $label = null)
     * @method Show\Field|Collection enter_time(string $label = null)
     * @method Show\Field|Collection floor(string $label = null)
     * @method Show\Field|Collection owner_name(string $label = null)
     * @method Show\Field|Collection owner_tel(string $label = null)
     * @method Show\Field|Collection rooms(string $label = null)
     * @method Show\Field|Collection unit(string $label = null)
     * @method Show\Field|Collection email_verified_at(string $label = null)
     */
    class Show {}

    /**
     
     */
    class Form {}

}

namespace Dcat\Admin\Grid {
    /**
     
     */
    class Column {}

    /**
     
     */
    class Filter {}
}

namespace Dcat\Admin\Show {
    /**
     
     */
    class Field {}
}
