<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchaseorder extends Model
{
    use HasFactory;

    protected $fillable = [
'cus_id',
'cus_name',
'cus_co',
'emp',
'create_on',
'fabric_id',
'fabric_pattern',
'fabric_structure',
'yarn_h_count',
'fabric_w',
'yarn_h_type1',
'sub_id_h1',
'sub_name_h1',
'yarn_h_count1',
'yarn_h_ratio1',
'yarn_h_type2',
'sub_id_h2',
'sub_name_h2',
'yarn_h_count2',
'yarn_h_ratio2',
'yarn_w_type1',
'sub_id_w1',
'sub_name_w1',
'yarn_w_count1',
'yarn_w_ratio1',
'yarn_w_type2',
'sub_id_w2',
'sub_name_w2',
'yarn_w_count2',
'yarn_w_ratio2',
'yarn_w_type3',
'sub_id_w3',
'sub_name_w3',
'yarn_w_count3',
'yarn_w_ratio3',
'yarn_w_type4',
'sub_id_w4',
'sub_name_w4',
'yarn_w_count4',
'yarnWRatio4',
'phew_number',
'phew_w',
'fabric_comment',
'machine_number',
'fabric_s',
'order_sum_yard',
'order_sum_m',
'fabric_spy',
'fabric_sp_p',
'price_yard',
'price_m',
'discount_p',
'discount_yard',
'commission',
'vat',
'purchase_order',
'po',
'deadline',
'comment',
    ];

}
