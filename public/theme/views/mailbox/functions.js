/**
 * Mailbox main helper functions
 */

var Mailbox = {
    toggleImportant: function toggleImportant(ids, cb) {

        
    },
    trash: function trash(ids, cb) {                    // move to the trash folder

        
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