$(document).ready(function () {
    $(document).on('click', '.execute', function () {
        let id = $(this).data('id');
        $('#result').html('<div class="text-muted small">Выполнение...</div>');

        $.post('execute.php', {id: id}, function (data) {
            $('#result').html(data);
        });
    });

    $(document).on('click', '.delete', function () {
        if (!confirm('Удалить запрос?')) return;

        $.post('delete.php', {id: $(this).data('id')}, function () {
            location.reload();
        });
    });

    $(document).on('click', '.edit', function () {
        $.get('get-query.php', {id: $(this).data('id')}, function (data) {
            let q = JSON.parse(data);
            $('select[name=user_id]').val(q.user_id);
            $('input[name=title]').val(q.title);
            $('textarea[name=sql_text]').val(q.sql_text);
            window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});
        });
    });

    $('#queryForm').submit(function (e) {
        e.preventDefault();

        $.post('save.php', $(this).serialize(), function () {
            location.reload();
        });
    });
});