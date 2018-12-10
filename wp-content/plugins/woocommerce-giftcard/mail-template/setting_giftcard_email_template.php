<?php
function setting_send_emai(){
echo "<form>";
    echo "<table width='100%' bgcolor='#2a2a2a' cellpadding='0' cellspacing='0' border='0' id='backgroundTable' st-sortable='seperator'>";
        echo "<caption><h1>";
            echo __('Setting email template', GIFTCARD_TEXT_DOMAIN);
        echo "</h1></caption>";
        echo "<tr>";
            echo "<th>";
                echo __('Email template', GIFTCARD_TEXT_DOMAIN);
            echo "</th>";
            echo "<td>";
                echo "<select name='email_template'>";
                    echo "<option value='1'>";
                        echo __('Active email template', GIFTCARD_TEXT_DOMAIN);
                    echo "</option>";
                    echo "<option value='0'>";
                        echo __('Inactive email template', GIFTCARD_TEXT_DOMAIN);
                    echo "</option>";
                echo "</select>";
            echo "</td>";
        echo "</tr>";
        echo "<tr>
            <td>";
                echo __('Logo company', GIFTCARD_TEXT_DOMAIN);
            echo "</td>
            <td>
                <input type='text' placeholder='links' name='logo_company'>
            </td>
        </tr>
        <tr>
            <td>";
                echo __('Image Banner', GIFTCARD_TEXT_DOMAIN);
            echo "</td>
            <td>
                <input type='text' placeholder='links' name='image_template'>
            </td>
        </tr>
        <tr>
            <td>";
                echo __('Email Subject', GIFTCARD_TEXT_DOMAIN);
            echo "</td>
            <td>
                <input type='text' placeholder='links' name='magenest_giftcard_to_subject'>
            </td>
        </tr>
        <tr>
            <td>";
                echo __('Email Content', GIFTCARD_TEXT_DOMAIN);
            echo "</td>
            <td>
                <input type='text' placeholder='links' name='magenest_giftcard_to_content'>
            </td>
        </tr>
        <tr>
            <td>";
                echo __('Email Footer', GIFTCARD_TEXT_DOMAIN);
            echo "</td>
            <td>
                <input type='textarea' placeholder='links' name='email_footer'>
            </td>
        </tr>
    </table>
</form>";
}