let news = document.getElementById('news');

if (news) {
    news.addEventListener('click', e => {
        if (e.target.className === 'btn btn-danger delete-new') {
            if (confirm('Вы действительно хотите удалить новость?')) {
                let slug = e.target.getAttribute('data-slug');

                fetch(`/news/destroy/${slug}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}
