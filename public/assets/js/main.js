const addTagFormDeleteLink = (item, type) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.classList.add('button');
    removeFormButton.classList.add('is-small');
    removeFormButton.classList.add('is-warning');
    removeFormButton.classList.add('mt-1');
    if (type == 'tagsPhoto'){
        removeFormButton.innerText = 'Supprimer cette photo';
    }else {
        removeFormButton.innerText = 'Supprimer cette vidéo';
    }

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        item.remove();
    });
}

const addFormToCollection = (e) => {
    const classElement = e.currentTarget.dataset.collectionHolderClass;
    const collectionHolder = document.querySelector('.' + classElement);

    const item = document.createElement('div');
    const itemIndex = collectionHolder.dataset.index;
    
    item.innerHTML = collectionHolder.dataset.prototype.replace(/__name__/g, collectionHolder.dataset.index);
    
    collectionHolder.appendChild(item);

    if (classElement == 'tagsPhoto') {
        const el = document.getElementById('trick_photoFigures_' + itemIndex + '_file');
        el.classList.add('input');
        el.classList.add('mt-4');
    } else {
        const el = document.getElementById('trick_videoFigures_' + itemIndex + '_path');
        el.classList.add('input');
        el.classList.add('mt-4');
        el.placeholder="Vidéo de la figure"
    }

    collectionHolder.dataset.index++;

    addTagFormDeleteLink(item, classElement);
};

const deleteFormInput = (e) => {
    e.preventDefault();
    const idLoop = e.target.id;

    item = document.getElementById('trick_videoFigures_'+idLoop+'_path');
    item2 = document.getElementById('error_'+idLoop);
    item3 = document.getElementById(idLoop);
    item.remove();
    item2.remove();
    item3.remove();
};

const deleteFormInputPhoto = (e) => {
    e.preventDefault();
    const idLoop = e.target.id;

    item = document.getElementById('trick_photoFigures_'+idLoop+'_file');
    item2 = document.getElementById('error_photo_'+idLoop);
    item3 = document.getElementById(idLoop);
    item.remove();
    item2.remove();
    item3.remove();
};

document.querySelectorAll('.add_item_link').forEach(btn => {
    btn.addEventListener("click", addFormToCollection)
});
document.querySelectorAll('.tricks_delete').forEach(btn => {
    btn.addEventListener("click", deleteFormInput)
});
document.querySelectorAll('.tricks_delete_photo').forEach(btn => {
    btn.addEventListener("click", deleteFormInputPhoto)
});