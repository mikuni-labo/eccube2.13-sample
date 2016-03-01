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
 * 最近見た商品表示プラグイン
 * プラグインのメインクラス
 */
class RecentAccessBlock extends SC_Plugin_Base
{
    //ブロックに表示する商品数（DEFAULT）
    const DEFAULT_DISP_PRODUCTS_COUNT = 4;
    
    // コンストラクタ
    public function __construct(array $arrSelfInfo)
    {
        parent::__construct($arrSelfInfo);
    }
    //インストール
    function install($arrPlugin)
    {
        // インストール処理の実装
    }
    // アンインストール
    function uninstall($arrPlugin)
    {
        // アンインストール処理の実装
    }
    //アップデート
    function update($arrPlugin)
    {
        // nop
    }
    //プラグインを有効
    function enable($arrPlugin)
    {
        //　プラグインを有効にした処理の実装
    }
    //プラグインを無効
    function disable($arrPlugin)
    {
        //　プラグイン停止した処理の実装
    }
    //処理の介入箇所とコールバック関数を設定
    function register(SC_Helper_Plugin $objHelperPlugin)
    {
        //TODO　プラグインインスタンス生成時処理の実装
        $objHelperPlugin->addAction('LC_Page_Products_Detail_action_after', array(&$this, 'setRecentAccessProductsCookie'));// コールバック関数
    }
    
    /**
     * アクセスした商品情報をクッキーに格納する処理
     * 
     * @param unknown $objPage
     */
    function setRecentAccessProductsCookie($objPage)
    {
        // Cookieから最近見た商品情報を取得
        $objCookie = new SC_Cookie_Ex(COOKIE_EXPIRE);
        $old_product_ids = $objCookie->getCookie('recent_access_products');
    
        // プラグイン情報を取得
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode("RecentAccessBlock");
        // DBに設定情報があればそれを、無ければ初期値をセット
        $productsCount = (isset($plugin['free_field1']) && $plugin['free_field1'] > 0) ? (int)$plugin['free_field1']: self::DEFAULT_DISP_PRODUCTS_COUNT;
    
        // 表示中の商品IDを取得
        $product_id = $objPage->tpl_product_id;
        if ($product_id)
        {
            $new_product_ids = array();
            $new_key = 0;
            // Cookieに現在の商品Idを保存
            $objCookie->setCookie('recent_access_products', null);
            $objCookie->setCookie('recent_access_products['.$new_key.']', (int)$product_id);
            
            // 最近みた商品の履歴を１つシフトする
            if (($productsCount > 1) && isset($old_product_ids))
            {
                foreach ($old_product_ids as $key => $id)
                {
                    if ($id != $product_id){
                        $new_key += 1;
                        
                        // Cookieに最近見た商品Idを保存
                        $objCookie->setCookie('recent_access_products['.$new_key.']', (int)$id);
                        if ($new_key == ($productsCount - 1)) {
                            break;
                        }
                    }
                }
            }
        }
    }
}
?>
