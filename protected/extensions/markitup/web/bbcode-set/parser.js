// -----------------------------------------------------------------------
// Copyright (c) 2008, Stone Steps Inc. 
// All rights reserved
// http://www.stonesteps.ca/legal/bsd-license/
//
// This is a BBCode parser written in JavaScript. The parser is intended
// to demonstrate how to parse text containing BBCode tags in one pass 
// using regular expressions.
//
// The parser may be used as a backend component in ASP or in the browser, 
// after the text containing BBCode tags has been served to the client. 
//
// Following BBCode expressions are recognized:
//
// [b]bold[/b]
// [i]italic[/i]
// [u]underlined[/u]
// [s]strike-through[/s]
// [samp]sample[/samp]
//
// [color=red]red[/color]
// [color=#FF0000]red[/color]
// [size=1.2]1.2em[/size]
//
// [url]http://blogs.stonesteps.ca/showpost.asp?pid=33[/url]
// [url=http://blogs.stonesteps.ca/showpost.asp?pid=33][b]BBCode[/b] Parser[/url]
//
// [q=http://blogs.stonesteps.ca/showpost.asp?pid=33]inline quote[/q]
// [q]inline quote[/q]
// [blockquote=http://blogs.stonesteps.ca/showpost.asp?pid=33]block quote[/blockquote]
// [blockquote]block quote[/blockquote]
//
// [pre]formatted 
//     text[/pre]
// [code]if(a == b) 
//   print("done");[/code]
//
//
// -----------------------------------------------------------------------
var opentags;           // open tag stack
var crlf2br = true;     // convert CRLF to <br>?
var urlstart = -1;      // beginning of the URL if zero or greater (ignored if -1)

// aceptable BBcode tags, optionally prefixed with a slash
var tagname_re = /^\/?(?:b|i|u|url|h1|h2|img|url|list|\*)$/;

// reserved, unreserved, escaped and alpha-numeric [RFC2396]
var uri_re = /^[-;\/\?:@&=\+\$,_\.!~\*'\(\)%0-9a-z]{1,512}$/i;

// main regular expression: CRLF, [tag=option], [tag] or [/tag]
var postfmt_re = /([\r\n])|(?:\[([*a-z0-9]{1,16})(?:=([^\x00-\x1F"'\(\)<>\[\]]{1,256}))?\])|(?:\[\/([*a-z0-9]{1,16})\])/ig;

// stack frame object
function taginfo_t(bbtag, etag)
{
	this.bbtag = bbtag;
	this.etag = etag;
}

// check if it's a valid BBCode tag
function isValidTag(str)
{
	if(!str || !str.length)
		return false;

	return tagname_re.test(str);
}

//
// m1 - CR or LF
// m2 - the tag of the [tag=option] expression
// m3 - the option of the [tag=option] expression
// m4 - the end tag of the [/tag] expression
//
function textToHtmlCB(mstr, m1, m2, m3, m4, offset, string)
{
	//
	// CR LF sequences
	//
	if(m1 && m1.length) {
		if(!crlf2br)
			return mstr;

		switch (m1) {
			case '\r':
				return "";
			case '\n':
				return "<br>";
		}
	}

	//
	// handle start tags
	//
	if(isValidTag(m2)) {

		// ignore any tags if there's an open option-less [url] tag
		if(opentags.length && opentags[opentags.length-1].bbtag == "url" && urlstart >= 0)
			return "[" + m2 + "]";

		switch (m2) {
			case "img":
				opentags.push(new taginfo_t(m2, "\" />"));

				// and treat the text following [img] as a URL
				return "<img src=\"";

			case "s":
				opentags.push(new taginfo_t(m2, "</span>"));
				return "<span style=\"text-decoration: line-through\">";

			case "list":
				if(m3 && uri_re.test(m3)) { // if we have start number
					opentags.push(new taginfo_t(m2, "</ol>"));
					return "<ol start=\"" + m3 + "\">";
				} else {
					opentags.push(new taginfo_t(m2, "</ul>"));
					return "<ul>";
				}

			case "*":
				return "<li>";

			case "url":
				opentags.push(new taginfo_t(m2, "</a>"));

				// check if there's a valid option
				if(m3 && uri_re.test(m3)) {
					// if there is, output a complete start anchor tag
					urlstart = -1;
					return "<a href=\"" + m3 + "\">";
				} else {
					// otherwise, remember the URL offset
					urlstart = mstr.length + offset;

					// and treat the text following [url] as a URL
					return "<a href=\"";
				}

			default:
				// [b], [i] and [u] don't need special processing
				opentags.push(new taginfo_t(m2, "</" + m2 + ">"));
				return "<" + m2 + ">";

		}
	}

	//
	// process end tags
	//
	if(isValidTag(m4)) {
		// highlight mismatched end tags
		if(!opentags.length || opentags[opentags.length-1].bbtag != m4)
			return "<span style=\"color: red\">[/" + m4 + "]</span>";

		if(m4 == "url") {
			// if there was no option, use the content of the [url] tag
			if(urlstart > 0)
				return "\">" + string.substr(urlstart, offset-urlstart) + opentags.pop().etag;

			// otherwise just close the tag
			return opentags.pop().etag;
		}

		// other tags require no special processing, just output the end tag
		return opentags.pop().etag;
	}

	return mstr;
}

//
// post must be HTML-encoded
//
function parseBBCode(post)
{
	var result, endtags, tag;

	// convert CRLF to <br> by default
	crlf2br = true;

	// create a new array for open tags
	if(opentags == null || opentags.length)
		opentags = new Array(0);

	// run the text through main regular expression matcher
	result = post.replace(postfmt_re, textToHtmlCB);

	// if there are any unbalanced tags, make sure to close them
	if(opentags.length) {
		endtags = new String();

		// if there's an open [url] at the top, close it
		if(opentags[opentags.length-1].bbtag == "url") {
			opentags.pop();
			endtags += "\">" + post.substr(urlstart, post.length-urlstart) + "</a>";
		}

		// close remaining open tags
		while(opentags.length)
			endtags += opentags.pop().etag;
	}

	return endtags ? result + endtags : result;
}

function previewBBCode(post) {
	return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\
		<html xmlns="http://www.w3.org/1999/xhtml">\
		<head>\
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\
			<link rel="stylesheet" type="text/css" href="~/template/preview.css" />\
		</head>\
		<body>\
		'+parseBBCode(post)+'\
		</body>\
		</html>';
}