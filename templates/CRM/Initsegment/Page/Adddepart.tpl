<h3>Donors with active donation and created since 1/-11-2013</h3>

<p>{$endText}</p>
<h3>Contacts added: </h3>
<table>
    <tr>
        <th>Id</th>
        <th>Name</th>
        <th>Created date</th>
        <th>Contribution id</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>
    {foreach from=$addedContacts item=addedContact}
        <tr>
            <td>{$addedContact.contact_id}</td>
            <td>{$addedContact.display_name}</td>
            <td>{$addContact.created_date}</td>
            <td>{$addedContact.contribution_id}</td>
            <td>{$addedContact.total_amount}</td>
            <td>{$addedContact.receive_date}</td>
        </tr>
    {/foreach}
</table>
