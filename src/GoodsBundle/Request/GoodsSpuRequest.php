<?php

declare(strict_types=1);

namespace GoodsBundle\Request;

use GoodsBundle\Repository\GoodsSpuRepository;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Rule;

class GoodsSpuRequest
{
    public function validGoodsSpuParams(ValidatorFactoryInterface $validatorFactory, array $params): array
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'array' => 'The :attribute field is array.',
            'integer' => 'The :attribute field is integer.',
            'boolean' => 'The :attribute field is boolean.',
            'in' => 'The :attribute field must in [' . implode(',', array_values(GoodsSpuRepository::ENUM_STATUS)) . '].',
        ];
        $rules = [
            'spu_name' => 'required',
            'spu_thumb' => 'required',
            'spu_images' => 'required|array',
            'spu_intro' => 'required',
            'status' => ['required', Rule::in(array_values(GoodsSpuRepository::ENUM_STATUS))],
            'sort' => 'integer:strict|min:0',
            'category_ids' => 'required|array',
            'brand_id' => 'required|integer|min:0',
            'open_params' => 'required|boolean:strict',
            'open_spec' => 'required|boolean:strict',
        ];
        $validator = $validatorFactory->make($params, $rules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        $params['open_params'] = is_bool($params['open_params']) ? $params['open_params'] : $params['open_params'] == 'true';
        $params['open_spec'] = is_bool($params['open_spec']) ? $params['open_spec'] : $params['open_spec'] == 'true';

        $validRules = [];
        if ($params['open_params']) {
            $validRules['params_value'] = 'required|array';
        }
        if ($params['open_spec']) {
            $validRules['spec_value_ids'] = 'required|array';
            $validRules['spec_value'] = 'required|array';
            $validRules['spec_value.*.spec_id'] = 'required|integer:strict|min:0';
            $validRules['spec_value.*.is_custom_spec'] = 'required|boolean:strict';
            $validRules['spec_value.*.spec_name'] = 'required';
            $validRules['spec_value.*.spec_sort'] = 'required|integer:strict|min:0';
            $validRules['spec_value.*.spec_value_id'] = 'required|integer|min:0';
            $validRules['spec_value.*.is_custom_spec_value'] = 'required|boolean:strict';
            $validRules['spec_value.*.is_default_spec_value'] = 'required|boolean:strict';
            $validRules['spec_value.*.spec_value_name'] = 'required';
//            $validRules['spec_value.*.spec_value_img'] = 'required';
            $validRules['spec_value.*.spec_value_sort'] = 'required|integer:strict|min:0';
            $validRules['sku_value'] = 'required|array';
            $validRules['sku_value.*.spec_value_key'] = 'required';
            $validRules['sku_value.*.spec_value_ids'] = 'required|array';
            $validRules['sku_value.*.spec_value_name'] = 'required';
            $validRules['sku_value.*.status'] = ['required', Rule::in(array_values(GoodsSpuRepository::ENUM_STATUS))];
            $validRules['sku_value.*.sku_code'] = 'required';
            $validRules['sku_value.*.sale_price'] = 'required|integer|min:0';
            $validRules['sku_value.*.stock_num'] = 'required|integer|min:0';
            $validRules['sku_value.*.open_images'] = 'required|boolean:strict';
            $validRules['sku_value.*.open_params'] = 'required|boolean:strict';
            $validRules['sku_value.*.open_intro'] = 'required|boolean:strict';
        } else {
            $validRules['sku_info'] = 'required|array';
            $validRules['sku_info.sku_code'] = 'required';
            $validRules['sku_info.stock_num'] = 'required|integer|min:0';
            $validRules['sku_info.sale_price'] = 'required|integer|min:0';
        }
        if (empty($validRules)) {
            return $params;
        }
        $validator = $validatorFactory->make($params, $validRules, $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }

        return $params;
    }
}
