const formSerialize = (formselector) => {
    let myform = document.querySelector(formselector),
        forment = new FormData(myform).entries()

    return Object.assign(...Array.from(forment, ([x, y]) => ({[x]: y})))
}

let $=jQuery;

jQuery(document).ready( ()=> {
    $(document).on('submit','.mphb_sc_checkout-form',(e)=> {
        let formData = formSerialize('.mphb_sc_checkout-form')

        $.ajax({
            url:"/wp-admin/admin-ajax.php",
            method:'POST',
            data: {action:'post_booking', check_out_data:formData},
            dataType:'json',
            async: false,
            beforeSend:(()=> {
                console.log("Please wait")
            })
        }).done((resp)=> {
            let response = JSON.parse(resp)
            console.log(response)
            let cf ="Confirmed";
            if (cf === "Confirmed") {
                alert("Reservation Book Successfully")
                e.returnValue = true
            }else if (response.status === "Unconfirmed"){
                alert("Reservation Book Unconfirmed")
                e.preventDefault()
            }else {
                alert("Reservation Book Failed")
                e.preventDefault()
            }
        })

    })

})
