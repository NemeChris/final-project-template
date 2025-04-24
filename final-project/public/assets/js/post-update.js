/**
 * This configuration was generated using the CKEditor 5 Builder. You can modify it anytime using this link:
 * https://ckeditor.com/ckeditor-5/builder/?redirect=portal#installation/NoNgNARATAdAzPCkAsc4AYAcUCsnkCcIcO6I6cIyAjJgXAdjngTnNSAOzpTJIQBTAHZJ0YYNTBixkmQF1IAgIYhiAYzgQ5QA
 */
let LICENSE_KEY;
$.ajax({
    url: "http://localhost/license",
    type: "GET",
    dataType: "json",
    async: false, 
    success: function(data) {
		console.log(data.licenseKey);
        LICENSE_KEY = data.licenseKey;
    },
    error: function(xhr, status, error) {
        console.error("Could not fetch license key:", error);
    }
});

const {
	ClassicEditor,
	Autoformat,
	AutoImage,
	Autosave,
	BlockQuote,
	Bold,
	CloudServices,
	Emoji,
	Essentials,
	Heading,
	ImageBlock,
	ImageCaption,
	ImageInline,
	ImageInsert,
	ImageInsertViaUrl,
	ImageResize,
	ImageStyle,
	ImageTextAlternative,
	ImageToolbar,
	ImageUpload,
	Indent,
	IndentBlock,
	Italic,
	Link,
	LinkImage,
	List,
	ListProperties,
	MediaEmbed,
	Mention,
	Paragraph,
	PasteFromOffice,
	SimpleUploadAdapter,
	Table,
	TableCaption,
	TableCellProperties,
	TableColumnResize,
	TableProperties,
	TableToolbar,
	TextTransformation,
	TodoList,
	Underline
} = window.CKEDITOR;

// const LICENSE_KEY = $env['WYSIWYG'];

const editorConfig = {
	toolbar: {
		items: [
			'heading',
			'|',
			'bold',
			'italic',
			'underline',
			'|',
			'emoji',
			'link',
			'insertImage',
			'mediaEmbed',
			'insertTable',
			'blockQuote',
			'|',
			'bulletedList',
			'numberedList',
			'todoList',
			'outdent',
			'indent'
		],
		shouldNotGroupWhenFull: false
	},
	plugins: [
		Autoformat,
		AutoImage,
		Autosave,
		BlockQuote,
		Bold,
		CloudServices,
		Emoji,
		Essentials,
		Heading,
		ImageBlock,
		ImageCaption,
		ImageInline,
		ImageInsert,
		ImageInsertViaUrl,
		ImageResize,
		ImageStyle,
		ImageTextAlternative,
		ImageToolbar,
		ImageUpload,
		Indent,
		IndentBlock,
		Italic,
		Link,
		LinkImage,
		List,
		ListProperties,
		MediaEmbed,
		Mention,
		Paragraph,
		PasteFromOffice,
		SimpleUploadAdapter,
		Table,
		TableCaption,
		TableCellProperties,
		TableColumnResize,
		TableProperties,
		TableToolbar,
		TextTransformation,
		TodoList,
		Underline
	],
	heading: {
		options: [
			{
				model: 'paragraph',
				title: 'Paragraph',
				class: 'ck-heading_paragraph'
			},
			{
				model: 'heading1',
				view: 'h1',
				title: 'Heading 1',
				class: 'ck-heading_heading1'
			},
			{
				model: 'heading2',
				view: 'h2',
				title: 'Heading 2',
				class: 'ck-heading_heading2'
			},
			{
				model: 'heading3',
				view: 'h3',
				title: 'Heading 3',
				class: 'ck-heading_heading3'
			},
			{
				model: 'heading4',
				view: 'h4',
				title: 'Heading 4',
				class: 'ck-heading_heading4'
			},
			{
				model: 'heading5',
				view: 'h5',
				title: 'Heading 5',
				class: 'ck-heading_heading5'
			},
			{
				model: 'heading6',
				view: 'h6',
				title: 'Heading 6',
				class: 'ck-heading_heading6'
			}
		]
	},
	image: {
		toolbar: [
			'toggleImageCaption',
			'imageTextAlternative',
			'|',
			'imageStyle:inline',
			'imageStyle:wrapText',
			'imageStyle:breakText',
			'|',
			'resizeImage'
		]
	},
	initialData:
		'',
	licenseKey: LICENSE_KEY,
	link: {
		addTargetToExternalLinks: true,
		defaultProtocol: 'https://',
		decorators: {
			toggleDownloadable: {
				mode: 'manual',
				label: 'Downloadable',
				attributes: {
					download: 'file'
				}
			}
		}
	},
	list: {
		properties: {
			styles: true,
			startIndex: true,
			reversed: true
		}
	},
	mention: {
		feeds: [
			{
				marker: '@',
				feed: [
					/* See: https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html */
				]
			}
		]
	},
	placeholder: 'Type or paste your content here!',
	table: {
		contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties']
	}
};

let editor;

ClassicEditor.create(document.querySelector('#editor'), editorConfig).then( newEditor => {
    editor = newEditor;
} )
.catch( error => {
    console.error( error );
} );


$(document).ready(function(){
    let urlArray = window.location.pathname.split("/");
	console.log(urlArray);
	let id = urlArray[2];
    function htmlDecode(input) {
        var doc = new DOMParser().parseFromString(input, "text/html");
        return doc.documentElement.textContent;
    }
    $.ajax({
        url: `http://localhost/posts/${id}`,
        type: "GET",
        dataType: "json",
        success: function(data){
            console.log(data);
            $('#title').val(data.title);
            if (editor) {
                let bodyCont = htmlDecode(data.content);
                let realBodyCont = htmlDecode(bodyCont);
                editor.setData(realBodyCont);
            } else {
                console.error("Editor not initialized yet");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
    $("#myForm").on('submit', function(e){
        e.preventDefault();
        const editorContent = editor.getData();
        const title = $('#title').val();
        let description = editorContent.substr(0, 80);
        description = description + "...";
        let myData = {
            editorContent, 
            description,
            title
        };
		console.log("testing form");
        $.ajax({
            url: `http://localhost/posts/${id}`,
            type: "PUT",
            dataType: "json",
            data: myData,
            success: function(data){
                console.log("succesful PUT");
				console.log(data);
                let urlID = data.id;
                let relURL = "/viewPost/" + urlID;
				window.location.href = relURL;
            },
            error: function(xhr, status, errorThrown) {
                console.error("AJAX Error Details:");
                console.error("Status:", status);
                console.error("Error:", errorThrown);
                console.error("Response Text:", xhr.responseText);
                console.error("Status Code:", xhr.status);
                alert("Update failed. See console for details.")
            }
        });
    });
})

