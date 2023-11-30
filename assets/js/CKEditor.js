import CKEditor from '@ckeditor/ckeditor5-build-classic'

//recebe id de um textarea para carregaro CKEDITORx
const createCKEditor = (creatId) => {
    CKEditor.create( document.querySelector( `#${creatId}` ),{
        // toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],

    } )
    .then( editor => {
        editor.setData(document.getElementById(creatId).value)
        editor.model.document.on( 'change:data', () => {
            document.getElementById(creatId).value = editor.getData()
        } );
    } )
    .catch( error => {
        console.error( error );
    } );
}

global.CKEditor  = CKEditor ;
global.createCKEditor  = createCKEditor ;

