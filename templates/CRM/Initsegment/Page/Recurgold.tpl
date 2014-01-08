<h3>Donors with active recurring contributions added to Gold</h3>

<p>{$endText}</p>
<h3>Contacts added: </h3>
<table>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Recur Id Id</th>
        <th>Amount</th>
        <th>Frequency</th>
    </tr>
    {foreach from=$addedContacts item=addedContact}
        <tr>
            <td>{$addedContact.contact_id}</td>
            <td>{$addedContact.display_name}</td>
            <td>{$addedContact.recur_id}</td>
            <td>{$addedContact.amount}</td>
            <td>{$addedContact.frequency}</td>
        </tr>
    {/foreach}
</table>
