import http from './interface'
import aa from '../config.js'
import jj from '../md5.js'
export const post = (url, data) => {
    let timestamp = _getTimeStamp();
    let token = uni.getStorageSync('token');
    let _s = _sStr(data);

    //设置请求前拦截器
    http.interceptor.request = (config) => {
        let u = aa.websiteUrl.split('/'),
            date = new Date(),
            _si = jj('key=' + aa.websiteSecret + '&' + _s + '&timestamp=' + (parseInt(timestamp *
                1000)) + u[date.toString().split(' ')[3].slice(0, 1)]);
        _si = _si.substring(5, 26).toUpperCase();
        config.header = {
            'secret': aa.websiteSecret,
            'token': token,
            'timestamp': timestamp,
            'sign': _si
        };
        config.method = 'POST';
    };
    //设置请求结束后拦截器
    http.interceptor.response = (response) => {
        if (response.data.code >= 400) {
            if (1200 == response.data.code) {
                uni.setStorageSync('userInfo', null);
                uni.navigateTo({
                    url: '/pages/user/login'
                })
                return false;
            }
            uni.showModal({
                title: '提示',
                content: response.data.msg || "请求失败",
                showCancel: false
            })
            return false;
        }
        return response;
    };
    return http.request({
        url: url,
        data
    });
};

export const get = (url, data) => {
    let timestamp = _getTimeStamp();
    let token = uni.getStorageSync('token');
    let _s = _sStr(data);
    //设置请求前拦截器
    http.interceptor.request = (config) => {
        let u = aa.websiteUrl.split('/'),
            date = new Date(),
            _si = jj('key=' + aa.websiteSecret + '&' + _s + '&timestamp=' + (parseInt(timestamp *
                1000)) + u[date.toString().split(' ')[3].slice(0, 1)]);
        _si = _si.substring(5, 26).toUpperCase();
        config.header = {
            'secret': aa.websiteSecret,
            'token': token,
            'timestamp': timestamp,
            'sign': _si
        };
        config.method = 'GET';
    }
    //设置请求结束后拦截器
    http.interceptor.response = (response) => {
        if (response.data.code >= 400) {
            uni.showModal({
                title: '提示',
                content: response.data.msg || "请求失败",
                showCancel: false
            })
            return false;
        }
        if (1200 == response.data.code) {
            uni.setStorageSync('userInfo', null);
            uni.navigateTo({
                url: '/pages/user/login'
            })
        }
        return response;
    }
    return http.request({
        url: url,
        data
    })
}
export default {
    post,
    get
}

const _sStr = (data) => {
    var ret = [];
    for (let it in data) {
        let val = data[it];
        if (typeof val === 'object' &&
            (!(val instanceof Array) || (val.length > 0 && ('object' === typeof val[0])))) {
            val = JSON.stringify(val);
        }
        ret.push((it + "=" + val));
    }
    // 字典升序
    ret.sort();
    return ret.join('&');
}

const _getTimeStamp = () => {
    let NowTime = new Date();
    NowTime = NowTime.getTime();
    return NowTime / 1000;
}
