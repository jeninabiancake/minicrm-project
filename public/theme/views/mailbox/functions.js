/**
 * Mailbox main helper functions
 */

var Mailbox = {
    toggleImportant: function toggleImportant(ids, cb) {

      $.ajax({
         url: BASE_URL + "/admin/mailbox-toggle-important",
         method: "PUT",
         data: {mailbox_flag_ids: ids, method: "PUT", _token: $("meta[name='csrf_token']").attr("content")},
         dataType: "json",
         success: function (response) {
              cb(response);
         }
      });   

        
    },
    trash: function trash(ids, cb) {                    // move to the trash folder

      $.ajax({
         url: BASE_URL + "/admin/mailbox-trash",
         method: "DELETE",
         data: {mailbox_user_folder_ids: ids, method: "DELETE", _token: $("meta[name='csrf_token']").attr("content")},
         dataType: "json",
         success: function (response) {
             cb(response);
         }
     });
        
    },
    send: function send(mailbox_id) {
       window.location.replace(BASE_URL + "/admin/mailbox-send/" + mailbox_id);
    },
    reply: function reply(mailbox_id) {
       window.location.replace(BASE_URL + "/admin/mailbox-reply/" + mailbox_id);
    },
    forward: function forward(mailbox_id) {
       window.location.replace(BASE_URL + "/admin/mailbox-forward/" + mailbox_id);
    }
   
    
};