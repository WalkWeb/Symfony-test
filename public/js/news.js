let news = document.getElementById('news');

if (news) {
    news.addEventListener('click', e => {
        if (e.target.className === 'btn btn-danger delete-new') {
        if (confirm('Вы действительно хотите удалить новость?')) {
            let id = e.target.getAttribute('data-id');

            fetch(`/news/destroy/${id}`, {
                method: 'DELETE'
            }).then(res => window.location.reload());
        }
    }
});
}