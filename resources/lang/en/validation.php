<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    | 驗證格式 使用 http status code 422 當開頭，再加上3碼變成自定義錯誤訊息 422XXX
    | EX: 422000
    |
    */
    'accepted' => '422000', // The :attribute must be accepted.
    'active_url' => '422001', // The :attribute is not a valid URL.
    'after' => '422002', // The :attribute must be a date after :date.
    'after_or_equal' => '422003', // The :attribute must be a date after or equal to :date.
    'alpha' => '422004', // The :attribute may only contain letters.
    'alpha_dash' => '422005', // The :attribute may only contain letters, numbers, dashes and underscores.
    'alpha_num' => '422006', // The :attribute may only contain letters and numbers.
    'array' => '422007', // The :attribute must be an array.
    'before' => '422008', // The :attribute must be a date before :date.
    'before_or_equal' => '422009', // The :attribute must be a date before or equal to :date.
    'between' => [
        'numeric' => '422010', // The :attribute must be between :min and :max.
        'file' => '422011', // The :attribute must be between :min and :max kilobytes.
        'string' => '422012', // The :attribute must be between :min and :max characters.
        'array' => '422013', // The :attribute must have between :min and :max items.
    ],
    'boolean' => '422014', // The :attribute field must be true or false.
    'confirmed' => '422015', // The :attribute confirmation does not match.
    'date' => '422016', // The :attribute is not a valid date.
    'date_equals' => '422017', // The :attribute must be a date equal to :date.
    'date_format' => '422018', // The :attribute does not match the format :format.
    'different' => '422019', // The :attribute and :other must be different.
    'digits' => '422020', // The :attribute must be :digits digits.
    'digits_between' => '422021', // The :attribute must be between :min and :max digits.
    'dimensions' => '422022', // The :attribute has invalid image dimensions.
    'distinct' => '422023', // The :attribute field has a duplicate value.
    'email' => '422024', // The :attribute must be a valid email address.
    'ends_with' => '422025', // The :attribute must end with one of the following: :values.
    'exists' => '422026', // The selected :attribute is invalid.
    'file' => '422027', // The :attribute must be a file.
    'filled' => '422028', // The :attribute field must have a value.
    'gt' => [
        'numeric' => '422029', // The :attribute must be greater than :value.
        'file' => '422030', // The :attribute must be greater than :value kilobytes.
        'string' => '422031', // The :attribute must be greater than :value characters.
        'array' => '422032', // The :attribute must have more than :value items.
    ],
    'gte' => [
        'numeric' => '422033', // The :attribute must be greater than or equal :value.
        'file' => '422034', // The :attribute must be greater than or equal :value kilobytes.
        'string' => '422035', // The :attribute must be greater than or equal :value characters.
        'array' => '422036', // The :attribute must have :value items or more.
    ],
    'image' => '422037', // The :attribute must be an image.
    'in' => '422038', // The selected :attribute is invalid.
    'in_array' => '422039', // The :attribute field does not exist in :other.
    'integer' => '422040', // The :attribute must be an integer.
    'ip' => '422041', // The :attribute must be a valid IP address.
    'ipv4' => '422042', // The :attribute must be a valid IPv4 address.
    'ipv6' => '422043', // The :attribute must be a valid IPv6 address.
    'json' => '422044', // The :attribute must be a valid JSON string.
    'lt' => [
        'numeric' => '422045', // The :attribute must be less than :value.
        'file' => '422046', // The :attribute must be less than :value kilobytes.
        'string' => '422047', // The :attribute must be less than :value characters.
        'array' => '422048', // The :attribute must have less than :value items.
    ],
    'lte' => [
        'numeric' => '422049', // The :attribute must be less than or equal :value.
        'file' => '422050', // The :attribute must be less than or equal :value kilobytes.
        'string' => '422051', // The :attribute must be less than or equal :value characters.
        'array' => '422052', // The :attribute must not have more than :value items.
    ],
    'max' => [
        'numeric' => '422053', // The :attribute may not be greater than :max.
        'file' => '422054', // The :attribute may not be greater than :max kilobytes.
        'string' => '422055', // The :attribute may not be greater than :max characters.
        'array' => '422056', // The :attribute may not have more than :max items.
    ],
    'mimes' => '422057', // The :attribute must be a file of type: :values.
    'mimetypes' => '422058', // The :attribute must be a file of type: :values.
    'min' => [
        'numeric' => '422059', // The :attribute must be at least :min.
        'file' => '422060', // The :attribute must be at least :min kilobytes.
        'string' => '422061', // The :attribute must be at least :min characters.
        'array' => '422062', // The :attribute must have at least :min items.
    ],
    'not_in' => '422063', // The selected :attribute is invalid.
    'not_regex' => '422064', // The :attribute format is invalid.
    'numeric' => '422065', // The :attribute must be a number.
    'password' => '422066', // The password is incorrect.
    'present' => '422067', // The :attribute field must be present.
    'regex' => '422068', // The :attribute format is invalid.
    'required' => '422069', // The :attribute field is required.
    'required_if' => '422070', // The :attribute field is required when :other is :value.
    'required_unless' => '422071', // The :attribute field is required unless :other is in :values.
    'required_with' => '422072', // The :attribute field is required when :values is present.
    'required_with_all' => '422073', // The :attribute field is required when :values are present.
    'required_without' => '422074', // The :attribute field is required when :values is not present.
    'required_without_all' => '422075', // The :attribute field is required when none of :values are present.
    'same' => '422076', // The :attribute and :other must match.
    'size' => [
        'numeric' => '422077', // The :attribute must be :size.
        'file' => '422078', // The :attribute must be :size kilobytes.
        'string' => '422079', // The :attribute must be :size characters.
        'array' => '422080', // The :attribute must contain :size items.
    ],
    'starts_with' => '422081', // The :attribute must start with one of the following: :values.
    'string' => '422082', // The :attribute must be a string.
    'timezone' => '422083', // The :attribute must be a valid zone.
    'unique' => '422084', // The :attribute has already been taken.
    'uploaded' => '422085', // The :attribute failed to upload.
    'url' => '422086', // The :attribute format is invalid.
    'uuid' => '422087', // The :attribute must be a valid UUID.

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
