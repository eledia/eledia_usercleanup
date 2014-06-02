This plugin deletes inactive user accounts. The cleanup process runs with the Moodle cron.
The cleanup-process runs in two steps for each user. If an inactive user is found he gets a notification mail and is marked as
notified within this plugin. When the user still has not accessed the system after a choosen timespan he will be deleted in the
second step.
The process checks the last access time stamp of the user against the present time stamp.
When a notified user loggs in again before the deletion process starts, his notify entry is removed with the next run of the
cleanup process.
You can configure how often the process is started by a time interval in days.

Installation:
To install eledia_usercleanup just copy the folder "eledia_usercleanup" into moodle/blocks/.
Afterwards you have to go to http://your-moodle/admin (Site administration -> Notifications) to trigger the installation process.

Using:
The block can be included by the admin on the main page. The block offers a link to the plugin configuration.
Set how often the process should start (days), the number of days since last login to mark the user as inactive and the number of
days after the notify mail until he gets deleted.

copyright  2013 eLeDia GmbH http://eledia.de
license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
You can receive a copy of the GNU General Public License at <http:www.gnu.org/licenses/>.
