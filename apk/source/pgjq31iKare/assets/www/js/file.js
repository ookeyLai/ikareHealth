var rootDir = targetDir = targetFile = "";
$.mobile.loadingMessage = "請稍候 ...";

function doFS() {
	errorCode=0;
	// 取得檔案系統
	window.requestFileSystem(LocalFileSystem.PERSISTENT,
						0, onFSSuccess, onFSFail);
}
function onFSSuccess(fileSystem) {
	rootDir = fileSystem.root; 
	targetDir = rootDir;
	showInfo("檔案系統: " + 
						fileSystem.name + "<br/>" +
						"根目錄名稱: " + fileSystem.root.name);
	errorCode=0;;
}	
function onFSFail(evt) {
//	alert("File system error: " + evt.target.error.code);
	alert("File system error: " + evt.code);
	errorCode=1;
}

function doMetadataFile() {
	errorCode=0;
	tmpDir = uploadDir;
	tmpDirFile = uploadFile;
//	console.log("before:targetDir="+targetDir.name+"\n tmpDir="+tmpDir+"\ntmpDirFile="+tmpDirFile);
	targetDir.getDirectory(tmpDir, { create: true, exclusive: false } , onTargetDir , onFSFail);
//	console.log("after:targetDir="+targetDir.name);

	targetDir.getFile(tmpDirFile, {create:true},
		function(fileEntry){
			fileEntry.file(metadataFile, onFSFail);
		}, onFSFail);
}
function onTargetDir(dirEntry) {
	targetDir = dirEntry;
	console.log("targetDir setTo:"+targetDir.name);
}
function metadataFile(file) {
	targetFile = file;
	console.log("targetFile setTo:"+targetFile.fullPath);
	d = new Date(file.lastModifiedDate);
	showInfo("檔案名稱: " + file.name + "<br/>" +
			"完整路徑: " + file.fullPath + "<br/>" +
			"修改日期: " + showDate(d) + "<br/>" +
			"檔案大小: " + file.size);
}

function doUploadFile() {
	$("#resultList").empty();
	$.mobile.showPageLoadingMsg("e","Please wait ...",true);
	// 取得上傳圖片的 URI
//	var img = document.getElementById('camera_image');
//	var imageURI = img.src;
	errorCode = 0;
	if (targetFile) { // 有檔案
		server = vDataSvrURL;
		if (server) {  // 建立  FileUploadOptions 物件
			var options = new FileUploadOptions ();
			options.fileKey = "uploadedfile";
			options.fileName = targetFile.name;
			options.mimeType = "text/plain";
			var params = new Object();  // 建立參數物件
//			params.value1 = "test";
//			params.value2 = "param";
			params.account_id = vAccountID;
			params.account_pw = vAccountPW;
			options.params = params;
			options.chunkedMode = false;
			var ft = new FileTransfer();    // 上傳檔案
//			console.log("targetFile:"+targetFile.fullPath+targetFile.name);
//			console.log("server:"+server);
			ft.upload(targetFile.fullPath, server, onFTSuccess,
						onFTFail, options);
		}
	} else {
		alert("data file net found: " + targetFile.name);
		errorCode = 1;
	}
}
function onFTSuccess(r) {
///	showInfo("上傳成功:" + r.bytesSent + "位元組");
	$.mobile.hidePageLoadingMsg();
	if (r.response=="AUTH_ERROR") {
		showInfoAppend("<br /><hr />上傳失敗:帳號或密碼錯誤!");
		erroorCode = 1;
	} else {
		showInfoAppend("<br /><hr />上傳成功:" + r.response);
		errorCode = 0;
	}
}
function onFTFail(error) {
	$.mobile.hidePageLoadingMsg();
	showInfo(error.response);
	console.log(error.code);
	errorCode = 1;
}

function doGetMessages() {
	var acc_id = 1;
	var sUrl = vMsgSvrURL+"?account_id="+vAccountID+"&account_pw="+vAccountPW;
	//showInfo(sUrl);
	$.mobile.showPageLoadingMsg("e","Please wait ...",true);
	$.ajax({
    	url: sUrl,
    	// type: "POST",
    	dataType: "json",
    	// data: {accout_id: vAccountID, account_pw: vAccountPW},
    	success: onMsgSuccess,
    	error: onMsgFail
    	});

}
function onMsgSuccess(data) {
	$("#resultList").empty();
	var str = "<li data-role='list-divider'>一般公告</li>";
	if (data.ALL.length>0) $("#resultList").append(str);
	for (var i=0; i<data.ALL.length; i++) {
							result = data.ALL[i];
							var str = "<li><a href='#'><h3>"+result.Time+"</h3>";
							str += "<p>"+result.Content+"</p></a>";
							str += "<ul><li><pre>"+result.Content+"</pre></li></ul></li>";
							$("#resultList").append(str);
							}
	var str = "<li data-role='list-divider'>專屬公告</li>";
	if (data.GROUP.length>0) $("#resultList").append(str);
	for (var i=0; i<data.GROUP.length; i++) {
							result = data.GROUP[i];
							var str = "<li><a href='#'><h3>"+result.Time+"</h3>";
							str += "<p>"+result.Content+"</p></a></li>";
							str += "<ul><li><pre>"+result.Content+"</pre></li></ul></li>";
							$("#resultList").append(str);
							}
	$("#resultList").listview("refresh");
	$.mobile.hidePageLoadingMsg();
	showInfo("收到健康訊息!");
}
function onMsgFail(error) {
	$("#resultList").empty();
	// showInfo("AJax Error!"+error.responseText);
	$.mobile.hidePageLoadingMsg();
	showInfo("帳號或密碼錯誤!");
}

function doBTdeviceTx() {
	showInfo("Do BT Device TX");
}