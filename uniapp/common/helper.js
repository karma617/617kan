// 通讯密匙
const websiteSecret = '';

let websiteUrl = '',apiImageUrl = '',imageUrl = '', shareUrl = '';

websiteUrl = 'https://域名/api.php';
apiImageUrl = 'https://域名';
imageUrl = 'https://域名/';

// APP分享到微信的H5页面地址，需配置微信开放平台APPID
shareUrl = ''; 

const cachePath = '/cache/';
const appName = '云影评';

var getUrlName = function getUrlName(url) {
	let tmp= new Array();//临时变量，保存分割字符串
	tmp = url.split("/");//按照"/"分割
	let pp = tmp[tmp.length-1];//获取最后一部分，即文件名和参数
	tmp = pp.split("?");//把参数和文件名分割开
	return tmp[0];
}

export default {  
    websiteSecret,
    websiteUrl,
    imageUrl,
    apiImageUrl,
    cachePath,
	getUrlName,
	appName,
	shareUrl,
}  