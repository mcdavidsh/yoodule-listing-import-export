const formSerialize = (formselector) => {
    let myform = document.querySelector(formselector),
        forment = new FormData(myform).entries()

    return Object.assign(...Array.from(forment, ([x, y]) => ({[x]: y})))
}

let $=jQuery;

jQuery(document).ready( ()=> {
    $('.mphb_sc_checkout-form').on('submit',(e)=> {
        e.preventDefault()
        let formData = formSerialize('.mphb_sc_checkout-form')
        $.ajax({
            url:"/wp-admin/admin-ajax.php",
            method:'POST',
            data: {action:'post_booking', check_out_data:formData},
            dataType:'json',
        }).done((response)=> {
            console.log(response)
        })
    })

})
