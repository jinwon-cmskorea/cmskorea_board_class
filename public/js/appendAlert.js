const appendAlert = (message, type, id) => {
    const alertPlaceholder = document.getElementById(id);
    const wrapper = document.createElement('div');
    wrapper.className = 'alertdivbox';
    wrapper.innerHTML = [
        '<div class="alert alert-', type, ' alert-dismissible alertmainbox" id="alertmain" >',
        '   <div>', message, '</div>',
        '   <button type="button" id="alertclose" class="btn-close close" data-bs-dismiss="alert"></button>',
        '</div>'
        ].join('');
    alertPlaceholder.append(wrapper);
}
const appendDeleteAlert = (message, id) => {
    const DeletePlaceholder = document.getElementById(id);
    const Deletewrapper = document.createElement('div')
    Deletewrapper.innerHTML = [
        '<div class="border border-danger border-2 rounded bg-danger-subtle text-dark p-2 alertDelete" style="position: absolute" id="alertDelete">',
        '   <div id="deletewrapperpk">', message, '</div>',
        '   <button type="button" id="DeleteCompleteClose" class="btn btn-danger DeleteCompleteClose">삭제</button>',
        '   <button type="button" id="DeleteClose" class="btn btn-secondary DeleteClose">취소</button>',
        '</div>'
    ].join('')
    DeletePlaceholder.append(Deletewrapper)
}