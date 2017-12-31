<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 26.12.17
 * Time: 20:27
 */

if (isset($_REQUEST['alertReason'])) {
    $alertType = null;
    $alertText = null;

    switch ($_REQUEST['alertReason']) {
        case 'bookCards_isset_UMID':
            $alertType = 'danger';
            $alertText = '<strong>Buchung abgebrochen!</strong> bookCards - UMID wurde nicht gesetzt!';
            break;
        case 'bookCards_isset_inputCount':
            $alertType = 'danger';
            $alertText = '<strong>Buchung abgebrochen!</strong> bookCards - inputCount wurde nicht gesetzt!';
            break;
        case 'bookCards_is_numeric_inputCount':
            $alertType = 'danger';
            $alertText = '<strong>Buchung abgebrochen!</strong> Bitte gib eine Zahl, aus Buchstaben und Umlauten kann man schwer eine Zahl auslesen!';
            break;
        case 'bookCards_between_1_20':
            $alertType = 'danger';
            $alertText = '<strong>Buchung abgebrochen!</strong> bookCards - inputCount ist nicht zwischen 1 und 20!';
            break;
        case 'bookCards_booked_more_Cards_than_available':
            $alertType = 'danger';
            $alertText = '<strong>Buchung abgebrochen!</strong> Es können nicht mehr Karten reserviert werden, als
                            wie zur Verfügung stehen!';
            break;
        case 'bookCards_successful':
            $alertType = 'success';
            $alertText = '<strong>Gebucht!</strong> Du hast erfolgreich <strong>' . $_REQUEST['bookedCards'] . '</strong>
                            Karten für <strong>' . $_REQUEST['movieName'] . '</strong> gebucht.';
            break;
        case 'cancelBookedCards_isset_UMID':
            $alertType = 'danger';
            $alertText = '<strong>Stornierung abgebrochen!</strong> cancelBookedCards - UMID wurde nicht gesetzt!';
            break;
        case 'cancelBookedCards_booking_does_not_exist':
            $alertType = 'danger';
            $alertText = '<strong>Stornierung abgebrochen!</strong> cancelBookedCards - Es exestiert keine Buchung welche
                            passen würde!';
            break;
        case 'cancelBookedCards_successful':
            $alertType = 'success';
            $alertText = '<strong>Storniert!</strong> Du hast erfolgreich deine Reservierung von 
                            <strong>' . $_REQUEST['movieName'] . '</strong> storniert.';
            break;
        case 'cancelWorkerGetInTouchWithMovie_isset_UMID':
            $alertType = 'danger';
            $alertText = '<strong>Entfernung als Arbeiter fehlgeschlagen!</strong> cancelWorkerGetInTouchWithMovie -
                            UMID wurde nicht gesetzt!';
            break;
        case 'cancelWorkerGetInTouchWithMovie_successful':
            $alertType = 'success';
            $alertText = '<strong>Entfernung als Arbeiter erfolgreich!</strong> Du hast dich erfolgreich als Arbeiter
                            für <strong>' . $_REQUEST['movieName'] . '</strong> abgemeldet.';
            break;
        case 'createMovie_isset_moviename':
            $alertType = 'danger';
            $alertText = '<strong>Film wurde nicht erstellt!</strong> createMovie - Moviename wurde nicht gesetzt!';
            break;
        case 'createMovie_isset_date':
            $alertType = 'danger';
            $alertText = '<strong>Film wurde nicht erstellt!</strong> createMovie - Date wurde nicht gesetzt!';
            break;
        case 'createMovie_isset_trailer':
            $alertType = 'danger';
            $alertText = '<strong>Film wurde nicht erstellt!</strong> createMovie - Trailer wurde nicht gesetzt!';
            break;
        case 'createMovie_successful':
            $alertType = 'success';
            $alertText = '<strong>Film erstellt! ' . $_REQUEST['movieName'] . '</strong> wurde erfolgreich erstellt.';
            break;
        case 'deleteMovie_isset_UMID':
            $alertType = 'danger';
            $alertText = '<strong>Film wurde nicht gelöscht!</strong> deleteMovie - UMID wurde nicht gesetzt!';
            break;
        case 'deleteMovie_successful':
            $alertType = 'success';
            $alertText = '<strong>Film gelöscht! ' . $_REQUEST['movieName'] . '</strong> wurde erfolgreich gelöscht.';
            break;
        case 'deleteUser_isset_UUID':
            $alertType = 'danger';
            $alertText = '<strong>Benutzer wurde nicht gelöscht</strong> deleteUser - UUID wurde nicht gesetzt!';
            break;
        case 'deleteUser_successful':
            $alertType = 'success';
            $alertText = '<strong>Benutzer gelöscht! ' . $_REQUEST['userName'] . '</strong> wurde erfolgreich gelöscht.';
            break;
        case 'editAccount_isset_firstname':
            $alertType = 'danger';
            $alertText = '<strong>Account wurde nicht bearbeitet!</strong> editAccount - firstname wurde nicht gesetzt!';
            break;
        case 'editAccount_isset_surname':
            $alertType = 'danger';
            $alertText = '<strong>Account wurde nicht bearbeitet!</strong> editAccount - surname wurde nicht gesetzt!';
            break;
        case 'editAccount_isset_email':
            $alertType = 'danger';
            $alertText = '<strong>Account wurde nicht bearbeitet!</strong> editAccount - email wurde nicht gesetzt!';
            break;
        case 'editAccount_isset_password_current':
            $alertType = 'danger';
            $alertText = '<strong>Account wurde nicht bearbeitet!</strong> editAccount - current password wurde nicht gesetzt!';
            break;
        case 'editAccount_new_passwords_are_not_equal':
            $alertType = 'danger';
            $alertText = '<strong>Account wurde nicht bearbeitet!</strong> Passwörter stimmen nicht überein!';
            break;
        case 'editAccount_current_password_is_wrong':
            $alertType = 'danger';
            $alertText = '<strong>Account wurde nicht bearbeitet!</strong> Dein derzeitiges Passwort stimmt nicht!';
            break;
        case 'editAccount_successful':
            $alertType = 'success';
            $alertText = '<strong>Account bearbeitet!</strong> Du hast deinen Account erfolgreich bearbeitet.';
            break;
        case 'editMovie_isset_UMID':
            $alertType = 'danger';
            $alertText = '<strong>Film wurde nicht bearbeitet!</strong> editMovie - UMID wurde nicht gesetzt!';
            break;
        case 'editMovie_isset_moviename':
            $alertType = 'danger';
            $alertText = '<strong>Film wurde nicht bearbeitet!</strong> editMovie - Moviename wurde nicht gesetzt!';
            break;
        case 'editMovie_isset_date':
            $alertType = 'danger';
            $alertText = '<strong>Film wurde nicht bearbeitet!</strong> editMovie - Date wurde nicht gesetzt!';
            break;
        case 'editMovie_isset_trailer':
            $alertType = 'danger';
            $alertText = '<strong>Film wurde nicht bearbeitet!</strong> editMovie - Trailer wurde nicht gesetzt!';
            break;
        case 'editMovie_successful':
            $alertType = 'success';
            $alertText = '<strong>Film bearbeitet! ' . $_REQUEST['movieName'] . '</strong> wurde erfolgreich bearbeitet!';
            break;
        case 'editRank_isset_UUID':
            $alertType = 'danger';
            $alertText = '<strong>Rang wurde nicht bearbeitet!</strong> editRank - UUID wurde nicht gesetzt!';
            break;
        case 'editRank_isset_rank':
            $alertType = 'danger';
            $alertText = '<strong>Rang wurde nicht bearbeitet!</strong> editRank - rank wurde nicht gesetzt!';
            break;
        case 'editRank_successful':
            $alertType = 'success';
            $alertText = '<strong>Rang bearbeitet!</strong> Der Rang von <strong>' . $_REQUEST['userName'] . '</strong>
                            wurde bearbeitet.';
            break;
        case 'editUserPassword_isset_UUID':
            $alertType = 'danger';
            $alertText = '<strong>Passwort wurde nicht bearbeitet!</strong> editUserPassword - UUID wurde nicht gesetzt!';
            break;
        case 'editUserPassword_isset_password':
            $alertType = 'danger';
            $alertText = '<strong>Passwort wurde nicht bearbeitet!</strong> editUserPassword - Password wurde nicht gesetzt!';
            break;
        case 'editUserPassword_isset_password_again':
            $alertType = 'danger';
            $alertText = '<strong>Passwort wurde nicht bearbeitet!</strong> editUserPassword - Password_again wurde nicht gesetzt!';
            break;
        case 'editUserPassword_passwords_are_not_equal':
            $alertType = 'danger';
            $alertText = '<strong>Passwort wurde nicht bearbeitet!</strong> Die Passwörter stimmen nicht überein!';
            break;
        case 'editUserPassword_passwords_only_512_characters':
            $alertType = 'danger';
            $alertText = '<strong>Passwort wurde nicht bearbeitet!</strong> Das Passwort darf nur 512 Zeichen lang sein!';
            break;
        case 'editUserPassword_successful':
            $alertType = 'success';
            $alertText = '<strong>Passwort bearbeitet!</strong> Das Passwort von 
                            <strong>' . $_REQUEST['userName'] . '</strong> wurde bearbeitet!';
            break;
        case 'getWorkerInTouchWithMovie_isset_UMID':
            $alertType = 'danger';
            $alertText = '<strong>Du wurdest nicht als Arbeiter entfernt!</strong> getWorkerInTouchWithMovie
                            - UMID wurde nicht gesetzt!';
            break;
        case 'getWorkerInTouchWithMovie_successful':
            $alertType = 'success';
            $alertText = '<strong>Erfolgreich als Arbeiter eingetragen!</strong> Du wurdest erfolgreich als Arbeiter 
                                für ' . $_REQUEST['movieName'] . ' eingetragen!';
            break;
        case 'permissionDenied':
            $alertType = 'danger';
            $alertText = '<strong>Nicht genug Berechtigungen!</strong> Du hast keine Berechtigungen für diese Aktion!';
            break;
        case 'loginFirst':
            $alertType = 'danger';
            $alertText = '<strong>Einloggen!</strong> Du musst dich zuerst einloggen!';
            break;
        case 'login_alreadyLoggedIn':
            $alertType = 'warning';
            $alertText = '<strong>Anmelden fehlgeschlagen!</strong> Du bist bereits angemeldet!';
            break;
        case 'login_isset_email':
            $alertType = 'danger';
            $alertText = '<strong>Anmelden fehlgeschlagen!</strong> login - Email wurde nicht gesetzt!';
            break;
        case 'login_isset_password':
            $alertType = 'danger';
            $alertText = '<strong>Anmelden fehlgeschlagen!</strong> login - Password wurde nicht gesetzt!';
            break;
        case 'login_credentials_wrong':
            $alertType = 'danger';
            $alertText = '<strong>Anmelden fehlgeschlagen!</strong> Entweder die Email-Adresse oder das Passwort ist falsch!';
            break;
        case 'login_successful':
            $alertType = 'success';
            $alertText = '<strong>Erfolgreich angemeldet!</strong> Du hast dich erfolgreich angemeldet!';
            break;
        case 'logout_successful':
            $alertType = 'success';
            $alertText = '<strong>Erfolgreich abgemeldet!</strong> Du hast dich erfolgreich abgemeldet!';
            break;
        case 'register_isset_firstname':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> register - firstname wurde nicht gesetzt!';
            break;
        case 'register_firstname_over_128_characters':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> Der Vorname darf nicht länger als 128 Zeichen sein!';
            break;
        case 'register_isset_surname':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> register - surname wurde nicht gesetzt!';
            break;
        case 'register_surname_over_128_characters':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> Der Nachname darf nicht länger als 128 Zeichen sein!';
            break;
        case 'register_isset_email':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> register - email wurde nicht gesetzt!';
            break;
        case 'register_email_over_128_characters':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> Die Email-Adresse darf nicht länger als 128 Zeichen sein!';
            break;
        case 'register_isset_password':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> register password wurde nicht gesetzt!';
            break;
        case 'register_password_over_512_characters':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> Das Passwort darf nicht länger als 128 Zeichen sein!';
            break;
        case 'register_isset_password_again':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> register - password_again wurde nicht gesetzt!';
            break;
        case 'register_passwords_are_not_equal':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> Die Passwörter stimmen nicht ein!';
            break;
        case 'register_account_already_exist':
            $alertType = 'danger';
            $alertText = '<strong>Registrierung fehlgeschlagen!</strong> Die Email-Adresse wurde bereits verwendet!';
            break;
        case 'register_successful':
            $alertType = 'success';
            $alertText = '<strong>Registrierung abgeschlossen!</strong> Du hast dich erfolgreich registriert. 
                            Jetzt nur noch anmelden.';
            break;
        default:
            $alertType = 'danger';
            $alertText = 'Unbekannter Alert Type wurde gefunden: ' . $_REQUEST['alertReason'];
            break;
    }

    echo '
        <div class="alert alert-' . $alertType . ' alert-dismissable fade in" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            ' . $alertText . '
        </div>
        ';

}