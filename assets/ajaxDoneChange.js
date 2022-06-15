let getAllCheckbox = document.querySelectorAll('input');

console.log(getAllCheckbox)

getAllCheckbox.forEach(checkbox => {
    checkbox.addEventListener('click', () => {
        fetch(`/task/${checkbox.id}/changeStatus`).catch((e) => console.log(e.message));
    })
})