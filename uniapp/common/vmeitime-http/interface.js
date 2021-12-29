import helper from '../helper';
export default {
	config: {
		baseUrl: helper.websiteUrl,
		header: {
			'Content-Type': 'text/html;charset=utf-8',
		},
		data: {},
		method: "POST",
		dataType: "json",
		responseType: "text",
		success() {},
		fail() {},
		complete() {}
	},
	interceptor: {
		request: null,
		response: null
	},
	request(options) {
		if (!options) {
			options = {}
		}
		options.baseUrl = options.baseUrl || this.config.baseUrl;
		options.dataType = options.dataType || this.config.dataType;
		options.url = options.baseUrl + options.url;
		options.data = options.data || {};
		options.method = options.method || this.config.method;
		return new Promise((resolve, reject) => {
			let _config = null;
			options.complete = (response) => {
				let statusCode = response.statusCode;
				response.config = _config;
				if (this.interceptor.response) {
					let newResponse = this.interceptor.response(response);
					if (newResponse) {
						response = newResponse;
					}
				}
				if (statusCode === 200) {
					resolve(response.data);
				} else {
					reject(response.data);
				}
			};
			_config = Object.assign({}, this.config, options);
			_config.requestId = new Date().getTime();
			if (this.interceptor.request) {
				this.interceptor.request(_config);
			}
			uni.request(_config);
		});
	},
	get(url, data, options) {
		if (!options) {
			options = {};
		}
		options.url = url;
		options.data = data;
		options.method = 'GET';
		return this.request(options);
	},
	post(url, data, options) {
		if (!options) {
			options = {};
		}
		options.url = url;
		options.data = data;
		options.method = 'POST';
		return this.request(options);
	},
	put(url, data, options) {
		if (!options) {
			options = {};
		}
		options.url = url;
		options.data = data;
		options.method = 'PUT';
		return this.request(options);
	},
	delete(url, data, options) {
		if (!options) {
			options = {};
		}
		options.url = url;
		options.data = data;
		options.method = 'DELETE';
		return this.request(options);
	}
};
