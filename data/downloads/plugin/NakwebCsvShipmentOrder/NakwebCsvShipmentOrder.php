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
 * プラグインのメインクラス
 *
 * @package NakwebCsvShipmentOrder
 * @author NAKWEB CO.,LTD.
 * @version $Id: $
 */
class NakwebCsvShipmentOrder extends SC_Plugin_Base {

    // 静的定数(CONSTはPHP5.3以降)
    private static $nakweb_plgin_individual = 'plg_nakweb_00001';
    private static $plg_nakweb_csv_id       = 79001;    // dtb_csv の csv_id

    /**
     * コンストラクタ
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * インストール
     * installはプラグインのインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin plugin_infoを元にDBに登録されたプラグイン情報(dtb_plugin)
     * @return void
     */
    function install($arrPlugin) {

        //======================================================================
        // 必要なファイルをコピーします.
        // ロゴ画像
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/logo.png", PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . "/logo.png");



        //======================================================================
        // 出力項目の定義
        $max_col = 0;
        $col_data = array();
        //// 出力項目

        //======================================================================
        //処理分岐の為にEC-CUBEのバージョンを取得
        $arrEcVersion = explode('.',ECCUBE_VERSION,3);

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

        //2.13.3以降では処理を分岐させる
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
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // トランザクション開始
        $objQuery->begin();

        // CSV出力項目の追加
        $db_sql = array();
        foreach ($col_data as $key => $arrVal) {

            // 出力項目の追加
            $db_sql['no']                     = $objQuery->nextVal('dtb_csv_no');
            $db_sql['csv_id']                 = NakwebCsvShipmentOrder::$plg_nakweb_csv_id;
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
     * アンインストール
     * uninstallはアンインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     * 
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function uninstall($arrPlugin) {
        // dbd_csv から 対象となる項目の削除をお香なう
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->delete('dtb_csv','csv_id = ?', array(NakwebCsvShipmentOrder::$plg_nakweb_csv_id));
    }

    /**
     * 稼働
     * enableはプラグインを有効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function enable($arrPlugin) {
        // nop
    }

    /**
     * 停止
     * disableはプラグインを無効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function disable($arrPlugin) {
        // nop
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     * 
     * @param $objHelperPlugin
     * @param $priority
     */
    function register($objHelperPlugin, $priority) {
        parent::register($objHelperPlugin, $priority);
        //// 管理画面用 CSV出力ボタンの追加
        $objHelperPlugin->addAction('prefilterTransform', array(&$this, 'prefilterTransform'), $this->arrSelfInfo['priority']);
    }

    // // スーパーフックポイント（preProcess）
    // function preProcess() {
    //     // nop
    // }

    // // スーパーフックポイント（prosess）
    // function prosess() {
    //     // nop
    // }





    //==========================================================================
    // Original Function
    //==========================================================================

    // テンプレートのフック（CSV出力用のボタン表示）
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . $this->arrSelfInfo['plugin_code'] . '/templates/';
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                if (strpos($filename, 'order/index.tpl') !== false) {
                    $objTransform->select('div.btn a.btn-normal',1)->insertAfter(file_get_contents($template_dir . 'plg_NakwebCsvShipmentOrder_admin_order_btn.tpl'));
                }
                break;
        }
        $source = $objTransform->getHTML();
    }


    /**
     * CSVファイルを出力する（配送先別，商品別，受注番号別 受注データCSV）
     *
     * @param LC_Page_Admin_Order $objPage
     * @return void
     */
    function output_nakweb_csv001($objPage) {

        if($objPage->getMode()=='nakweb_csv_001'){

            $objFormParam = new SC_FormParam_Ex();
            LC_Page_Admin_Order::lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);

            $objFormParam->convParam();
            $objFormParam->trimParam();

            $where = 'del_flg = 0';
            $arrWhereVal = array();
            foreach ($objPage->arrHidden as $key => $val) {
                if ($val == '') {
                    continue;
                }
                LC_Page_Admin_Order::buildQuery($key, $where, $arrWhereVal, $objFormParam);
            }

            $order = 'update_date DESC';

            if ($where != '') {
                $where = " WHERE $where ";
            }

            // CSV出力
            $this->sfDownloadCsv(NakwebCsvShipmentOrder::$plg_nakweb_csv_id, $where, $arrWhereVal, $order, true);

            exit;
        }
    }


    /**
     * CSVファイルを送信する
     *
     * @param integer $csv_id CSVフォーマットID
     * @param string $where WHERE条件文
     * @param array $arrVal プリペアドステートメントの実行時に使用される配列。配列の要素数は、クエリ内のプレースホルダの数と同じでなければなりません。
     * @param string $order ORDER文
     * @param boolean $is_download true:ダウンロード用出力までさせる false:CSVの内容を返す(旧方式、メモリを食います。）
     * @return mixed $is_download = true時 成功失敗フラグ(boolean) 、$is_downalod = false時 string
     */
    function sfDownloadCsv($csv_id, $where = '', $arrVal = array(), $order = '', $is_download = false) {

        $objCSV = new SC_Helper_CSV_Ex();

        $this->arrSubnavi = array($csv_id => 'shipmentcsv',);
        $this->arrSubnaviName = array($csv_id => '配送先別，商品別 受注詳細データ',);

        // 実行時間を制限しない
        @set_time_limit(0);

        // CSV出力タイトル行の作成
        $arrOutput = SC_Utils_Ex::sfSwapArray($objCSV->sfGetCsvOutput($csv_id, 'status = ' . CSV_COLUMN_STATUS_FLG_ENABLE));
        if (count($arrOutput) <= 0) return false; // 失敗終了
        $arrOutputCols = $arrOutput['col'];

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->setOrder($order);
        $cols = SC_Utils_Ex::sfGetCommaList($arrOutputCols, true);

        // 受注番号別，商品別，配送先別 受注データ CSV
        $sql = 'SELECT ' . $cols . ' FROM ( SELECT * FROM dtb_order ' . $where . ' ) AS dtb_order '
             . ' LEFT JOIN dtb_order_detail '
             . '   ON dtb_order.order_id = dtb_order_detail.order_id '
             . ' LEFT JOIN dtb_shipment_item '
             . '   ON dtb_order_detail.product_class_id = dtb_shipment_item.product_class_id '
             . '  AND dtb_order_detail.order_id = dtb_shipment_item.order_id '
             . ' LEFT JOIN dtb_shipping '
             . '   ON dtb_shipment_item.order_id = dtb_shipping.order_id '
             . '  AND dtb_shipment_item.shipping_id = dtb_shipping.shipping_id ';

        // SQL文からクエリ実行し CSVファイルを送信する
        return $objCSV->sfDownloadCsvFromSql($sql, $arrVal, $this->arrSubnavi[$csv_id], $arrOutput['disp_name'], $is_download);
    }



}
?>
