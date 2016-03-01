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

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 商品詳細マトリクス表示の設定クラス
 */
class LC_Page_Plugin_RecentAccessBlock_Config extends LC_Page_Admin_Ex {

    var $arrForm = array();

    /**
     * 初期化する.
     * @return void
    */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."RecentAccessBlock/templates/config.tpl";
        $this->tpl_subtitle = "ユーザーが最近見た商品 設定画面";
    }
    /**
     * プロセス.s
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }
    /**
     * Page のアクション.
     * @return void
     */
    function action() {
        $objFormParam = new SC_FormParam_Ex();
        $this->lnitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $arrForm = array();

        switch ($this->getMode()) {
            case 'edit':
                $arrForm = $objFormParam->getHashArray();
                $this->arrErr = $objFormParam->checkError();
                // エラーなしの場合にはデータを更新
                if (count($this->arrErr) == 0) {
                    // データ更新
                    $this->arrErr = $this->updateData($arrForm);
                    if (count($this->arrErr) == 0) {
                        $this->tpl_onload = "alert('登録が完了しました。');";
                    }
                }
                break;
            default:
                // プラグイン情報を取得.
                $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("RecentAccessBlock");
                $arrForm['free_field1'] = $plugin['free_field1'];
                break;
        }
        $this->arrForm = $arrForm;
        $this->setTemplate($this->tpl_mainpage);
    }
    /**
     * デストラクタ.
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
    /**
     * パラメーター情報の初期化
     * @param object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    function lnitParam(&$objFormParam) {
        $objFormParam->addParam('表示商品数', 'free_field1', INT_LEN, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK', 'ZERO_CHECK'));
    }
    /**
     * プラグイン情報の更新
     * @param type $arrData
     * @return type
     */
    function updateData($arrData) {
        $arrErr = array();
            
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = $arrData['free_field1'];
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'RecentAccessBlock'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);
         
        $result = $objQuery->commit();
        return $arrErr;
    }
}
?>
