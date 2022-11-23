const addTagFormDeleteLink = (item, type) => {
    const removeFormButton = document.createElement('button');
    removeFormButton.classList.add('button');
    removeFormButton.classList.add('is-small');
    removeFormButton.classList.add('is-warning');
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
    } else {
        const el = document.getElementById('trick_videoFigures_' + itemIndex + '_path');
        el.classList.add('input');
        el.placeholder="Vidéo de la figure"
    }

    collectionHolder.dataset.index++;

    addTagFormDeleteLink(item, classElement);
};

document.querySelectorAll('.add_item_link').forEach(btn => {
    btn.addEventListener("click", addFormToCollection)
});