//this is for thumbnail
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------

var jcrop_api;
var boundx;
var boundy;

var $preview = ""
var $pcnt = "";
var $pimg = "";

var xsize = "";
var ysize = "";


function showThumbnailCropper() {
	// Grab some information about the preview pane
	$preview = $('#preview-pane-thumbnail');
	$pcnt = $('#preview-pane-thumbnail .preview-container');
	$pimg = $('#preview-pane-thumbnail .preview-container img');

	xsize = $pcnt.width();
	ysize = $pcnt.height();

	$('#cropper-thumbnail').Jcrop({
		boxWidth : 380,
		boxHeight : 380,
		onChange : updatePreviewPane,
		onSelect : updatePreviewPane,
		onRelease : clearCoords,
		setSelect : [ 0, 0, 203, 164 ],
	}, function() {

		var bounds = this.getBounds();
		boundx = bounds[0];
		boundy = bounds[1];

		jcrop_api = this;

		// disable selection and resize
		jcrop_api.setOptions({
			allowSelect : false
		});
		jcrop_api.setOptions({
			allowResize : true
		});

		$preview.appendTo(jcrop_api.ui.holder);
	});
}

// Simple event handler, called from onChange and onSelect
// event handlers, as per the Jcrop invocation above
function updatePreviewPane(c) {
	$('#thumbnail  input[name="x-coordinate1"]').val(c.x);
	$('#thumbnail  input[name="y-coordinate1"]').val(c.y);
	$('#thumbnail  input[name="x-coordinate2"]').val(c.x2);
	$('#thumbnail  input[name="y-coordinate2"]').val(c.y2);
	$('#thumbnail  input[name="width"]').val(c.w);
	$('#thumbnail  input[name="height"]').val(c.h);

	if (parseInt(c.w) > 0) {
		var rx = xsize / c.w;
		var ry = ysize / c.h;

		$pimg.css({
			width : Math.round(rx * boundx) + 'px',
			height : Math.round(ry * boundy) + 'px',
			marginLeft : '-' + Math.round(rx * c.x) + 'px',
			marginTop : '-' + Math.round(ry * c.y) + 'px'
		});
	}

};

// clear the coordinates
function clearCoords() {
	$('#coords input').val('');
};





// this is for cropping
// ------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------


var jcrop_api_cropper;
var boundx_cropper;
var boundy_cropper;

var $preview_cropper = ""
var $pcnt_cropper = "";
var $pimg_cropper = "";

var xsize_cropper = "";
var ysize_cropper = "";



function showCropper() {
	
	// Grab some information about the preview pane
	$preview_cropper = $('#preview-pane-cropper');
	$pcnt_cropper = $('#preview-pane-cropper .preview-container');
	$pimg_cropper = $('#preview-pane-cropper .preview-container img');

	xsize_cropper = $pcnt_cropper.width();
	ysize_cropper = $pcnt_cropper.height();

	$('#cropper-cropping').Jcrop({
		boxWidth : 380,
		boxHeight : 380,
		onChange : updatePreviewPaneCropper,
		onSelect : updatePreviewPaneCropper,
		onRelease : clearCoords,
		setSelect : [ 0, 0, 203, 164 ],
	}, function() {

		var bounds = this.getBounds();
		boundx_cropper = bounds[0];
		boundy_cropper = bounds[1];

		jcrop_api_cropper = this;

		$preview_cropper.appendTo(jcrop_api_cropper.ui.holder);
	});

	$("#change_selection").live("click", (function(){
		width  = $("#cropping input[name=width]").val();
		height = $("#cropping input[name=height]").val();
		
		jcrop_api_cropper.animateTo([ 0, 0, width, height], function(){
			$('#cropping input[name="width"]').val(width);
			$('#cropping input[name="height"]').val(height);
		});
	}));
}


function updatePreviewPaneCropper(c) {
	$('#cropping input[name="x-coordinate1"]').val(c.x);
	$('#cropping input[name="y-coordinate1"]').val(c.y);
	$('#cropping input[name="x-coordinate2"]').val(c.x2);
	$('#cropping input[name="y-coordinate2"]').val(c.y2);
	
	$('#cropping input[name="width"]').val(parseInt(c.w));
	$('#cropping input[name="height"]').val(parseInt(c.h));
	
	if (parseInt(c.w) > 0) {
		var rx = xsize_cropper / c.w;
		var ry = ysize_cropper / c.h;

		$pimg_cropper.css({
			width : Math.round(rx * boundx_cropper) + 'px',
			height : Math.round(ry * boundy_cropper) + 'px',
			marginLeft : '-' + Math.round(rx * c.x) + 'px',
			marginTop : '-' + Math.round(ry * c.y) + 'px'
		});
	}

};
