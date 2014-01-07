/*jslint browser:true, sloppy:true*/
/*global ActiveXObject*/

/*
Hjax - simple ajax wrapper

Usage: hjax(url[, options][, callback]);

url can be relative or absolute

options is a key/value object containing the following settable properties:
	log: boolean or a custom function to log with. Default false.
	headers: object. Request headers. Default {"X-Requested-With": "XMLHttpRequest"}
	method: string. Default "GET"
	username: string. For http basic auth. Default ""
	password: string. For http basic auth. Default ""
	data: string. post body data. Default ""
	parseJSON: boolean. Whether to try to JSON.parse the response. Default true.

callback takes the following arguments: (error, data, xhr)
	error will be set if options.parseJSON is true and the data does not parse.
	data is either the parsed or raw data from the server
	xhr is the xhr object, allowing access to .status, .getAllResponseHeaders, etc.

hjax will automatically set content-length and encoding if options.data is set
and you have not aleady set them in options.headers

hjax will set method to POST if you specify options.data and not options.method
or if options.method === "GET"
*/

function hjax(url, options, callback) {

	var me, xhr, postString, key, headerName;

	// Who am I?
	me = "hjax";

	// Make options optional
	if (callback === undefined) {
		// options.log(me, "no options given");
		callback = options;
		options = {};
	}
	// Make callback optional
	if (callback === undefined) {
		options.log(me, "no callback given");
		callback = function noCallback() {
			options.log(me, "empty callback", arguments);
		};
	}

	// Validate arguments
	if (typeof options !== "object") {
		callback("options must be an object");
		return;
	}

	// Add default options
	options.headers		= options.headers	|| {};
	options.method		= options.method	|| "GET";
	options.username	= options.username	|| "";
	options.password	= options.password	|| "";
	options.parseJSON	= options.parseJSON	|| true;
	options.data		= options.data		|| "";

	// Stringify data
	postString = "";
	if (typeof options.data === "object") {
		options.log(me, "stringifying data");
		for (key in options.data) {
			if (options.data.hasOwnProperty(key)) {
				if (postString !== "") {
					postString += "&";
				}
				postString += key + "=" + options.data[key];
			}
		}
		// Cut off leading &:
		//postString = postString.substring(1);
		// ^ slower than doing the if in the loop: http://jsperf.com/if-substring
		// I suspect there's a crossover point here, where large objects
		// become slower than substringing after the loop

	} else if (typeof options.data === "string") {
		postString = options.data;
	} else {
		callback("cannot stringify data");
		return;
	}

	// Set default logger if boolean sent,
	// else ensure is function or off
	if (options.log === true) {
		options.log = console.log;
	} else if (typeof options.log !== "function") {
		// Not logging. Use noop. Saves if (options.log) everywhere.
		options.log = function noLog() {};
	}

	// Make method uppercase
	options.method = options.method.toUpperCase();

	// Autopopulate post headers
	if (options.data.length > 0) {
		// Assume post if not specified.
		if (options.method === "GET") {
			options.method = "POST";
		}
		// Add content length.
		if (!options.headers.hasOwnProperty("Content-length")) {
			options.headers["Content-length"] = options.data.length;
		}
		// Add content type.
		if (!options.headers.hasOwnProperty("Content-type")) {
			options.headers["Content-type"] = "application/x-www-form-urlencoded";
		}
	}

	// Set X-Requested-With unless it's been set already
	// (this allows devs to set to blank to not send this, while defaulting to send it)
	if (!options.headers.hasOwnProperty("X-Requested-With")) {
		options.headers["X-Requested-With"] = "XMLHttpRequest";
	}

	options.log(me, "options", options);

	// Get the XMLHttpRequest object
	xhr = window.XMLHttpRequest ?
			new XMLHttpRequest() :
			new ActiveXObject('Microsoft.XMLHTTP');

	options.log(me, "got xhr", xhr);

	// Open request (3rd param is isAsync)
	options.log(me, "xhr.open", options.method, url, true, options.username, options.password);
	xhr.open(options.method, url, true, options.username, options.password);

	// Set headers
	for (headerName in options.headers) {
		if (options.headers.hasOwnProperty(headerName)) {
			options.log(me, "xhr.setRequestHeader", headerName, options.headers[headerName]);
			xhr.setRequestHeader(headerName, options.headers[headerName]);
		}
	}

	// Add the listener
	xhr.onreadystatechange = function readyStateChanged() {

		var parsedJson;

		options.log(me, "readystate changed to " + xhr.readyState);

		if (xhr.readyState === 4) {
			// Try to parse json if appropriate.
			// getResponseHeader is not case sensitive, which is nice.
			if (options.parseJSON && xhr.getResponseHeader("Content-type") === "application/json") {
				options.log(me, "attempting to parse responseText as JSON", xhr.responseText);
				try {
					parsedJson = JSON.parse(xhr.responseText);
				} catch (e) {
					options.log(me, "Failed to parse", e);
					options.log(me, "calling back with an error parameter and the raw data");
					callback(e, xhr.responseText, xhr);
					return;
				}
				options.log(me, "calling back with JS literal", parsedJson);
				callback(false, parsedJson, xhr);
				return;
			}

			options.log(me, "calling back with raw data", xhr.responseText);
			callback(false, xhr.responseText, xhr);
		}
	};


	// Send the request, along with any post data
	options.log(me, "xhr.send", postString);
	xhr.send(postString);

	// Return the xhr object in case developer needs to call .abort etc
    return xhr;
}
