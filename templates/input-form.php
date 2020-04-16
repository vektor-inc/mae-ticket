<?php get_header(); ?>

<div class="container">
    <div id="maetick_input">
        <form>
            <input type="number" name="number-1" id="number-1" />
            <span class="_border">-</span>
            <input type="number" name="number-2" id="number-2" />
            <span class="_border">-</span>
            <input type="number" name="number-3" id="number-3" />
            <span class="_border">-</span>
            <input type="number" name="number-4" id="number-4" />
            <input type="submit" />
        </form>
    </div>
</div>

<script type="text/javascript">
((window, document) => {
    let p = document.getElementById('maetick_input');
    if (!p) return;

    let numbers = [
        document.getElementById('number-1'),
        document.getElementById('number-2'),
        document.getElementById('number-3'),
        document.getElementById('number-4')
    ]

    let valid = (t)=>{
        if (Number.isInteger(Number(t.value)) && t.value.length == 4) {
            t.classList.add('ok')
            t.classList.remove('error')
        }else{
            t.classList.remove('ok')
            t.classList.add('error')
        }
    }
    for(let i=0;i<numbers.length;i++){
        numbers[i].setAttribute('digit', i);
        numbers[i].addEventListener('keyup', (e)=>{
            let t = e.target;
            let digit = Number(t.getAttribute('digit'));
            if(48 <= e.keyCode && e.keyCode <= 57) {
                valid(t)
                if (t.value.length == 4 && digit < numbers.length-1) {
                    numbers[digit+1].focus()
                    numbers[digit+1].select()
                }
            }
        })

        numbers[i].addEventListener('keydown', (e)=>{
            let t = e.target;
            let digit = Number(t.getAttribute('digit'));
            if(48 <= e.keyCode && e.keyCode <= 57) {
                if(t.value.length == 4)e.preventDefault()
            }
            if(e.keyCode == 8) {
                if(t.value.length == 0 && digit > 0) {
                    t.value = '';
                    t.classList.remove('ok', 'error');
                    numbers[digit-1].focus()
                }
            }
        })
    }
})(window, document);

</script>

<?php get_footer(); ?>
