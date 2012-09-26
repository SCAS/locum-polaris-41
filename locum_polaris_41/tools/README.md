At the time of writing this, there was a bug in the stored procedure that prevented many of the overlaid bib items from being returned.
While waiting for the fix, I created a custom view on the Polaris SQL database that effectively does the same thing. So In order for
this tool to work, a view will need to be created in the [Polaris] database:

View name: `!!SOPAC_BibReplacement`
Once saved, it should show up in the database as: `dbo.!!SOPAC_BibReplacement`

Here is the SQL:

```SQL

SELECT td.numValue AS OldBibRecordID,
       td1.numValue AS NewBibRecordID,
       th.TranClientDate
FROM PolarisTransactions.polaris.TransactionHeaders th WITH (nolock)
INNER JOIN PolarisTransactions.polaris.TransactionDetails td WITH (nolock) ON (th.TransactionID = td.TransactionID
                                                                               AND td.TransactionSubTypeID = 38)
INNER JOIN PolarisTransactions.polaris.TransactionDetails td1 WITH (nolock) ON (th.TransactionID = td1.TransactionID
                                                                                AND td1.TransactionSubTypeID = 278)
WHERE th.TransactionTypeID = 3024
    AND td1.numValue IS NOT NULL
    AND td1.numValue > 0
UNION
SELECT td.numValue AS OldBibID,
       td1.numValue AS NewBibID,
       th.TranClientDate
FROM PolarisTransactions.polaris.TransactionHeaders th WITH (nolock)
INNER JOIN PolarisTransactions.polaris.TransactionDetails td WITH (nolock) ON (th.TransactionID = td.TransactionID
                                                                               AND td.TransactionSubTypeID = 36)
INNER JOIN PolarisTransactions.polaris.TransactionDetails td1 WITH (nolock) ON (th.TransactionID = td1.TransactionID
                                                                                AND td1.TransactionSubTypeID = 279)
WHERE th.TransactionTypeID = 3024
    AND td1.numValue IS NOT NULL
    AND td1.numValue > 0
UNION
SELECT td.numValue AS OldBibRecordID,
       td1.numValue AS NewBibRecordID,
       th.TranClientDate
FROM PolarisTransactions.polaris.TransactionHeaders th WITH (nolock)
INNER JOIN PolarisTransactions.polaris.TransactionDetails td WITH (nolock) ON (th.TransactionID = td.TransactionID
                                                                               AND td.TransactionSubTypeID = 38)
INNER JOIN PolarisTransactions.polaris.TransactionDetails td1 WITH (nolock) ON (th.TransactionID = td1.TransactionID
                                                                                AND td1.TransactionSubTypeID = 278)
WHERE th.TransactionTypeID = 3001
    AND td1.numValue IS NOT NULL
    AND td1.numValue > 0
UNION
SELECT td.numValue AS OldBibID,
       td1.numValue AS NewBibID,
       th.TranClientDate
FROM PolarisTransactions.polaris.TransactionHeaders th WITH (nolock)
INNER JOIN PolarisTransactions.polaris.TransactionDetails td WITH (nolock) ON (th.TransactionID = td.TransactionID
                                                                               AND td.TransactionSubTypeID = 36)
INNER JOIN PolarisTransactions.polaris.TransactionDetails td1 WITH (nolock) ON (th.TransactionID = td1.TransactionID
                                                                                AND td1.TransactionSubTypeID = 278)
WHERE th.TransactionTypeID = 3001
    AND td1.numValue IS NOT NULL
    AND td1.numValue > 0

```