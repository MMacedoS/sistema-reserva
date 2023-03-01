    

<script>
    $('#sair').click(function(){
        window.location.reload();
    });

    $('#buttonBg').click(function(){
        event.preventDefault();
        var code = "<?=$_SESSION['code']?>";
        $.post('<?=ROTA_GERAL?>/Professor/changeStatusBg/'+ code, [],function(retorna){
                window.location.reload();
        });
    });
</script>
        </div>
    </body>
</html>