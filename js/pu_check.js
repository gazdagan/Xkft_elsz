/* 
 * pénztár ellenötzés js pot ajax
 */


function penzugyicheck_post(){
    
    console.log("pénzügyi ellenörzés check");
    
    var adatok = {
        pe_date : "",
        pe_recepcio : "",
        pe_info : "err",
        pe_telephely : "",
    };    
    
    adatok.pe_info =  document.getElementById("summernote").value;
    adatok.pe_recepcio = document.getElementById("loginname").innerHTML;
    adatok.pe_telephely = document.getElementById("select_telehely").innerHTML;
    adatok.pe_date = document.getElementById("pe_date").value;
    
    
    
    postAjax("./PostGetAjax/post_napi_pu_check.php", adatok, function(data){ 
        
        console.log(data); 
        document.getElementById("safe_check").innerHTML = "&nbsp;" + data;
          
    
    
    });
   
}
