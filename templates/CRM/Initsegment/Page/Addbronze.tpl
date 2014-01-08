<h3>Donors with donation before last 18 mnths to bronze</h3>

<p>{$endText}</p>
<h3>Contacts added: </h3>
<table>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Number of contributions</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>
    {foreach from=$addedContacts item=addedContact}
        <tr>
            <td>{$addedContact.contact_id}</td>
            <td>{$addedContact.display_name}</td>
            <td>{$addedContact.contributions}</td>
        </tr>
    {/foreach}
</table>
