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
    // コンストラクタ
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }
    //インストール
    function install($arrPlugin) {
        //TODOインストール処理の実装
    }
    // アンインストール
    function uninstall($arrPlugin) {
        //TODOアンインストール処理の実装
    }
    //アップデート
    function update($arrPlugin) {
        // nop
    }
    //プラグインを有効
    function enable($arrPlugin) {
        //TODO　プラグインを有効にした処理の実装
    }
    //プラグインを無効
    function disable($arrPlugin) {
        //TODO　プラグイン停止した処理の実装
    }
    //処理の介入箇所とコールバック関数を設定
    function register(SC_Helper_Plugin $objHelperPlugin) {
        //TODO　プラグインインスタンス生成時処理の実装
    }
}
?>
