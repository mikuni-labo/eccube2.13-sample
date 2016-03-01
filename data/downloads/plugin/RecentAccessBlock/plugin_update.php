<?php
/*
 * NakwebCsvShipmentOrder
 * Copyright (C) 2012 NAKWEB CO.,LTD. All Rights Reserved.
 * http://www.nakweb.com/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/**
 * プラグイン のアップデート用クラス.
 *
 * @package NakwebCsvShipmentOrder
 * @author NAKWEB CO.,LTD.
 * @version $Id: $
 */
class plugin_update{
   /**
     * アップデート
     * updateはアップデート時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function update($arrPlugin) {
        switch($arrPlugin['plugin_version']){
        //今回はバージョンが0.1でも1.1でも同じ処理をするので、条件別けの中にbreakを書きません
        // バージョン0.1からのアップデート
        case("0.1"):
        //バージョン1.1からのアップデート
        case("1.1"):
        //バージョン1.2からのアップデート
        case("1.2"):
        //バージョン1.3からのアップデート
        case("1.3"):
        //バージョン1.4からのアップデート
        case("1.4"):
           plugin_update::updatever($arrPlugin);
           plugin_update::insertFookPoint($arrPlugin);
           break;
        default:
           break;
        }
    }

    /**
     * 0.1と1.1のアップデートを実行します.
     * @param type $param 
     */
    function updatever($arrPlugin) {

        // 変更のあったファイルを上書きします.
        //// プログラムファイル
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/plugin_info.php", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/plugin_info.php");
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/NakwebCsvShipmentOrder.php", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/NakwebCsvShipmentOrder.php");

        // プラグイン用の変数設定
        $plugin_data  = '';
        //$arrData   = array();
        //$plugin_data  = serialize($arrData);

        // CSV用項目の設定
        plugin_update::setDatabase($arrPlugin);

        // dtb_pluhinを更新します.
        plugin_update::updateDtbPluginData($arrPlugin, $plugin_data);
    }

    /**
     * dtb_pluginを更新します.
     * 
     * @param int $arrPlugin プラグイン情報
     * @return void
     */
    function updateDtbPluginData($arrPlugin, $plugin_data = '') {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $sqlval = array();
        $table = "dtb_plugin";
        if (strlen($plugin_data) > 0) {
            // データが存在している場合は更新する（シリアライズ化を事前に行なっておくこと）
            $sql_conf['free_field1']    = $plugin_data;
        }
        //$sql_conf['plugin_name']        = '';
        //$sql_conf['plugin_description'] = '';
        $sql_conf['plugin_version']     = '1.5';
        $sql_conf['compliant_version']  = '2.12.2 ～ 2.13.5';
        $sql_conf['update_date']        = 'CURRENT_TIMESTAMP';
        $where = "plugin_id = ?";
        $objQuery->update($table, $sql_conf, $where, array($arrPlugin['plugin_id']));
    }

    /**
     * CSV データベースの内容設定（本来は別ファイルでインストール時と共通化するべき）
     *
     * @param array $arrPlugin plugin_infoを元にDBに登録されたプラグイン情報(dtb_plugin)
     * @return void
     */
    function setDatabase($arrPlugin) {

        $plg_nakweb_csv_id = 79001;    // dtb_csv の csv_id

        // トランザクション開始
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        // dbd_csv から 対象となる項目の削除
        $objQuery->delete('dtb_csv','csv_id = ?', array($plg_nakweb_csv_id));

        //======================================================================
        // 出力項目の定義 インストールと同内容
        $max_col = 0;
        $col_data = array();
        //// 出力項目

        //-------------------------------------------------
        // dtb_order
        //-------------------------------------------------
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_id";
        $col_data[$max_col]['disp_name'] = "注文番号";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.customer_id";
        $col_data[$max_col]['disp_name'] = "会員ID";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.message";
        $col_data[$max_col]['disp_name'] = "要望等";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_name01";
        $col_data[$max_col]['disp_name'] = "お名前(姓)";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_name02";
        $col_data[$max_col]['disp_name'] = "お名前(名)";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_kana01";
        $col_data[$max_col]['disp_name'] = "お名前(フリガナ・姓)";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_kana02";
        $col_data[$max_col]['disp_name'] = "お名前(フリガナ名)";

        $arrEcVersion = explode('.',ECCUBE_VERSION,3);
        if($arrEcVersion[1] >= '13'){
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_company_name";
        $col_data[$max_col]['disp_name'] = "会社名";
        }
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_email";
        $col_data[$max_col]['disp_name'] = "メールアドレス";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_tel01";
        $col_data[$max_col]['disp_name'] = "電話番号1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_tel02";
        $col_data[$max_col]['disp_name'] = "電話番号2";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_tel03";
        $col_data[$max_col]['disp_name'] = "電話番号3";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_fax01";
        $col_data[$max_col]['disp_name'] = "FAX1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_fax02";
        $col_data[$max_col]['disp_name'] = "FAX2";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_fax03";
        $col_data[$max_col]['disp_name'] = "FAX3";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_zip01";
        $col_data[$max_col]['disp_name'] = "郵便番号1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_zip02";
        $col_data[$max_col]['disp_name'] = "郵便番号2";
        $max_col++;
        $col_data[$max_col]['col']       = "(SELECT name FROM mtb_pref WHERE mtb_pref.id = dtb_order.order_pref) as order_pref";
        $col_data[$max_col]['disp_name'] = "都道府県";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_addr01";
        $col_data[$max_col]['disp_name'] = "住所1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_addr02";
        $col_data[$max_col]['disp_name'] = "住所2";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_sex";
        $col_data[$max_col]['disp_name'] = "性別";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_birth";
        $col_data[$max_col]['disp_name'] = "生年月日";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.order_job";
        $col_data[$max_col]['disp_name'] = "職種";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.subtotal";
        $col_data[$max_col]['disp_name'] = "小計";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.discount";
        $col_data[$max_col]['disp_name'] = "値引き";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.deliv_fee";
        $col_data[$max_col]['disp_name'] = "送料";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.charge";
        $col_data[$max_col]['disp_name'] = "手数料";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.use_point";
        $col_data[$max_col]['disp_name'] = "使用ポイント";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.add_point";
        $col_data[$max_col]['disp_name'] = "加算ポイント";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.tax";
        $col_data[$max_col]['disp_name'] = "税金";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.total";
        $col_data[$max_col]['disp_name'] = "合計";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.payment_total";
        $col_data[$max_col]['disp_name'] = "お支払い合計";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.deliv_id";
        $col_data[$max_col]['disp_name'] = "配送業者ID";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.payment_method";
        $col_data[$max_col]['disp_name'] = "支払い方法";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.note";
        $col_data[$max_col]['disp_name'] = "SHOPメモ";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.status";
        $col_data[$max_col]['disp_name'] = "対応状況";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.create_date";
        $col_data[$max_col]['disp_name'] = "注文日時";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.update_date";
        $col_data[$max_col]['disp_name'] = "更新日時";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.commit_date";
        $col_data[$max_col]['disp_name'] = "発送完了日時";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order.device_type_id";
        $col_data[$max_col]['disp_name'] = "端末種別ID";
        $max_col++;
        $col_data[$max_col]['col']       = "(SELECT COUNT(shipping_id) as shipping_target_num FROM dtb_shipping WHERE dtb_shipping.order_id = dtb_order.order_id) as order_shipping_num";
        $col_data[$max_col]['disp_name'] = "配送先数";
        $max_col++;
        $col_data[$max_col]['col']       = "(SELECT ARRAY_TO_STRING(ARRAY(SELECT shipping_id FROM dtb_shipping WHERE dtb_shipping.order_id = dtb_order.order_id), ',')) as order_shipping_ids";
        $col_data[$max_col]['disp_name'] = "配送情報ID";


        //-------------------------------------------------
        // dtb_order_detail
        //-------------------------------------------------
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order_detail.order_detail_id";
        $col_data[$max_col]['disp_name'] = "注文番号詳細";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order_detail.product_id";
        $col_data[$max_col]['disp_name'] = "商品ID";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order_detail.product_class_id";
        $col_data[$max_col]['disp_name'] = "商品規格ID";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order_detail.product_name";
        $col_data[$max_col]['disp_name'] = "商品名";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order_detail.product_code";
        $col_data[$max_col]['disp_name'] = "商品コード";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order_detail.classcategory_name1";
        $col_data[$max_col]['disp_name'] = "商品規格名1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_order_detail.classcategory_name2";
        $col_data[$max_col]['disp_name'] = "商品規格名2";


        //-------------------------------------------------
        // dtb_shipment_item
        //-------------------------------------------------
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipment_item.price";
        $col_data[$max_col]['disp_name'] = "単価";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipment_item.quantity";
        $col_data[$max_col]['disp_name'] = "個数";


        //-------------------------------------------------
        // dtb_shipping
        //-------------------------------------------------
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_name01";
        $col_data[$max_col]['disp_name'] = "配送先お名前(姓)";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_name02";
        $col_data[$max_col]['disp_name'] = "配送先お名前(名)";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_kana01";
        $col_data[$max_col]['disp_name'] = "配送先お名前(フリガナ・姓)";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_kana02";
        $col_data[$max_col]['disp_name'] = "配送先お名前(フリガナ名)";
        $arrEcVersion = explode('.',ECCUBE_VERSION,3);
        if($arrEcVersion[1] >= '13'){
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_company_name";
        $col_data[$max_col]['disp_name'] = "配送先会社名";
        }
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_tel01";
        $col_data[$max_col]['disp_name'] = "配送先電話番号1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_tel02";
        $col_data[$max_col]['disp_name'] = "配送先電話番号2";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_tel03";
        $col_data[$max_col]['disp_name'] = "配送先電話番号3";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_fax01";
        $col_data[$max_col]['disp_name'] = "配送先FAX1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_fax02";
        $col_data[$max_col]['disp_name'] = "配送先FAX2";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_fax03";
        $col_data[$max_col]['disp_name'] = "配送先FAX3";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_zip01";
        $col_data[$max_col]['disp_name'] = "配送先郵便番号1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_zip02";
        $col_data[$max_col]['disp_name'] = "配送先郵便番号2";
        $max_col++;
        $col_data[$max_col]['col']       = "(SELECT name FROM mtb_pref WHERE mtb_pref.id = dtb_shipping.shipping_pref) as shipping_pref";
        $col_data[$max_col]['disp_name'] = "配送先都道府県";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_addr01";
        $col_data[$max_col]['disp_name'] = "配送先住所1";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_addr02";
        $col_data[$max_col]['disp_name'] = "配送先住所2";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_time";
        $col_data[$max_col]['disp_name'] = "配送時間";
        $max_col++;
        $col_data[$max_col]['col']       = "dtb_shipping.shipping_date";
        $col_data[$max_col]['disp_name'] = "配送予定日";
        if($arrEcVersion[1] >= '13'){
            if($arrEcVersion[2] <= '2'){
                $max_col++;
                $col_data[$max_col]['col']       = "dtb_shipping.shipping_num";
                $col_data[$max_col]['disp_name'] = "配送伝票番号";
            }
        } else {
            $max_col++;
            $col_data[$max_col]['col']       = "dtb_shipping.shipping_num";
            $col_data[$max_col]['disp_name'] = "配送伝票番号";
        }
        //$max_col++;
        //$col_data[$max_col]['col']       = "dtb_shipping.time_id";
        //$col_data[$max_col]['disp_name'] = "配送時間ID";
        //$max_col++;
        //$col_data[$max_col]['col']       = "dtb_shipping.shipping_commit_date";
        //$col_data[$max_col]['disp_name'] = "発送日時";



        //======================================================================
        // dtb_csv への 項目追加

        // CSV出力項目の追加
        $db_sql = array();
        foreach ($col_data as $key => $arrVal) {

            // 出力項目の追加
            $db_sql['no']                     = $objQuery->nextVal('dtb_csv_no');
            $db_sql['csv_id']                 = $plg_nakweb_csv_id;
            $db_sql['col']                    = $arrVal['col'];
            $db_sql['disp_name']              = $arrVal['disp_name'];
            $db_sql['rank']                   = $key;
            $db_sql['rw_flg']                 = 1;
            $db_sql['status']                 = 1;
            $db_sql['create_date']            = "CURRENT_TIMESTAMP";
            $db_sql['update_date']            = "CURRENT_TIMESTAMP";
            $db_sql['mb_convert_kana_option'] = "";
            $db_sql['size_const_type']        = "";
            $db_sql['error_check_types']      = "";

            // 出力項目の追加（SQLの実行）
            $objQuery->insert("dtb_csv", $db_sql);

        }

        // コミット
        $objQuery->commit();

    }

    /**
     * バージョン1.5では登録されていなかったフックポイントを登録する。
     * 
     * @param int $arrPlugin プラグイン情報
     * @return void
     */
    function insertFookPoint($arrPlugin) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // フックポイントをDB登録.
        $hook_point = array(
            array("LC_Page_Admin_Order_action_after", 'output_nakweb_csv001')
        );
        /**
         * FIXME コードが重複しているため、要修正
         */
        // フックポイントが配列で定義されている場合
        if (is_array($hook_point)) {
            foreach ($hook_point as $h) {
                $arr_sqlval_plugin_hookpoint = array();
                $id = $objQuery->nextVal('dtb_plugin_hookpoint_plugin_hookpoint_id');
                $arr_sqlval_plugin_hookpoint['plugin_hookpoint_id'] = $id;
                $arr_sqlval_plugin_hookpoint['plugin_id'] = $arrPlugin['plugin_id'];
                $arr_sqlval_plugin_hookpoint['hook_point'] = $h[0];
                $arr_sqlval_plugin_hookpoint['callback'] = $h[1];
                $arr_sqlval_plugin_hookpoint['update_date'] = 'CURRENT_TIMESTAMP';
                $objQuery->insert('dtb_plugin_hookpoint', $arr_sqlval_plugin_hookpoint);
            }
        // 文字列定義の場合
        } else {
            $array_hook_point = explode(',', $hook_point);
            foreach ($array_hook_point as $h) {
                $arr_sqlval_plugin_hookpoint = array();
                $id = $objQuery->nextVal('dtb_plugin_hookpoint_plugin_hookpoint_id');
                $arr_sqlval_plugin_hookpoint['plugin_hookpoint_id'] = $id;
                $arr_sqlval_plugin_hookpoint['plugin_id'] = $arrPlugin['plugin_id'];
                $arr_sqlval_plugin_hookpoint['hook_point'] = $h;
                $arr_sqlval_plugin_hookpoint['update_date'] = 'CURRENT_TIMESTAMP';
                $objQuery->insert('dtb_plugin_hookpoint', $arr_sqlval_plugin_hookpoint);
            }
        }
        
        $objQuery->commit();
    }

}
?>
