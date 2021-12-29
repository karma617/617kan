<?php
namespace app\index\server;

use app\common\server\Service;

class Config extends Service{

    public function initialize() {
        parent::initialize();
    }

    public function getConfig($data, $user) {
        $data = [
            'base_url' => config('videos.base_url'),
            'show_ad' => (int)config('commoncfg.show_ad'),
            'shake' => (int)config('commoncfg.shake'),
            'ad_switch' => (int)config('commoncfg.ad_switch'),
            'player_show_ad' => (int)config('commoncfg.player_show_ad'),
            'ad_close' => (int)config('commoncfg.ad_close'),
            'player_bottom_ad' => (int)config('commoncfg.player_bottom_ad'),
            'show_xx' => (int)config('commoncfg.show_xx'),
            'show_video_ad' => (int)config('commoncfg.show_video_ad'),
            'show_popup' => (int)config('commoncfg.show_popup'),
            'popup_img' => config('commoncfg.popup_img'),
            'popup_title' => config('commoncfg.popup_title'),
            'play_start_ad' => (int)config('playad.play_start_ad'),
            'should_play_end' => (int)config('playad.should_play_end'),
            'play_ad_num' => (int)config('playad.play_ad_num'),
            'play_ad_switch' => (int)config('playad.play_ad_switch'),
            'play_ad_title' => config('playad.play_ad_title'),
            'play_ad_text' => config('playad.play_ad_text'),
            'member_group_switch' => (int)config('commoncfg.member_group_switch'),
            'member_group_limit' => (int)config('commoncfg.member_group_limit'),
            'userinfo' => $user ? $user : []
        ];
        return $data;
    }
	
	public function updateInfo() {
        $data = [
            'ios' => [
                'version' => config('update_app.ios_version'),  // 后台填写的当前最新版本的app版本号，和客户端的globalConfigs.js里面配置的iosVersion如果相等则不跟新，如果不相等且atOnce为true时，会提示更新
                'download_url' => config('update_app.ios_download_url'), // 需要下载的url，此url为应用市场的下载地址
                'log' => config('update_app.ios_log'),  // 更新日志内容，有后台填写并返回
                'atOnce' => config('update_app.ios_atonce') == 0 ? false : true    // 如果有更新，是否立即提示更新，为false时不更新，为true时会提示更新，次提示可用于app上架审核期间控制不提示更新
            ],
            'android' => [
                'version' => config('update_app.android_version'),  // 后台填写的当前最新版本的app版本号，和客户端的globalConfigs.js里面配置的iosVersion如果相等则不跟新，如果不相等且atOnce为true时，会提示更新
                'download_url' => config('update_app.android_download_url'), // "http://sapp.lhave.net/download/fuapp.apk", 需要下载的url，此url为应用的下载地址
                'log' => config('update_app.android_log'),  // 更新日志内容，有后台填写并返回
                'atOnce' => config('update_app.android_atonce') == 0 ? false : true    // 如果有更新，是否立即提示更新，为false时不更新，为true时会提示更新，次提示可用于app上架审核期间控制不提示更新
            ]
        ];
        return $data;
    }
}