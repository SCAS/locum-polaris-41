Bib Harvest/Scrape

Array
(
    [bnum] => Passed
    [bib_created] => SQL1
    [bib_lastupdate] => SQL1
    [bib_prevupdate] => SQL1
    [modified] => SQL1
    [bib_revs] => HARD
    [lang] => SQL1
    [loc_code] => HARD
    [mat_code] => SQL1
    [suppress] => SQL1
    [author] => SQL1
    [addl_author] => a:2:{i:0;s:20:"Whelan, Julia, 1984-";i:1;s:16:"Heyborne, Kirby.";}
    [title] => SQL1
    [title_medium] => SQL1
    [edition] => Library ed.; Unabridged.
    [series] => 
    [callnum] => SQL1
    [pub_info] => SQL2
    [pub_year] => SQL1
    [stdnum] => SQL2
    [upc] => SQL1
    [cover_img] => 
    [download_link] => 
    [lccn] => 
    [descr] => SQL2
    [notes] => 
)


SELECT TOP 16
	br.BibliographicRecordID AS bnum,
	br.CreationDate AS bib_created,
	br.ModificationDate AS bib_lastupdate,
	br.ModificationDate AS bib_prevupdate,
	'1' AS bib_revs,
	br.MARCLanguage AS lang,
	'unused' AS loc_code,
	mtm.MARCTypeOfMaterialID AS mat_code,
	((br.DisplayInPAC - 1) * -1) AS suppress,
	br.BrowseAuthor AS author,
	br.BrowseTitle AS title,
	LOWER(br.MARCMedium) AS title_medium,
	br.BrowseCallNo AS CallNumber,
	br.PublicationYear AS pub_year,
	upc.UPCNumber AS upc

FROM [Polaris].[Polaris].[BibliographicRecords] AS br WITH (NOLOCK) 
LEFT OUTER JOIN [Polaris].[Polaris].[MARCTypeOfMaterial] AS mtm WITH (NOLOCK) ON mtm.MARCTypeOfMaterialID = br.PrimaryMARCTOMID
LEFT OUTER JOIN [Polaris].[Polaris].[BibliographicUPCIndex] AS upc WITH (NOLOCK) ON upc.BibliographicRecordID = br.BibliographicRecordID


---

SELECT TOP 1000 tag.[BibliographicTagID]
      ,[BibliographicRecordID]
      ,[Sequence]
      ,[TagNumber]
      ,[IndicatorOne]
      ,[IndicatorTwo]
      ,[EffectiveTagNumber]
      ,[SubfieldSequence]
      ,[Subfield]
      ,[Data]
  FROM [Polaris].[Polaris].[BibliographicTags] AS tag
  

  
  LEFT OUTER JOIN [Polaris].[Polaris].[BibliographicSubfields] AS sub
  ON tag.BibliographicTagID = sub.BibliographicTagID
  

  
  WHERE tag.[BibliographicRecordID] = 157203
  
ORDER BY [EffectiveTagNumber] ASC, [Sequence] ASC, [SubfieldSequence] ASC

// polaris_bib_record

Array
(
    [bnum] => 159983
    [bib_created] => 2012-08-10 08:56:53
    [bib_lastupdate] => 2012-08-10 08:56:53
    [bib_prevupdate] => 2012-08-10 08:56:53
    [bib_revs] => 1
    [lang] => eng
    [loc_code] => unused
    [mat_code] => 1
    [suppress] => 0
    [author] => Flynn, Gillian, 1971-
    [title] => Gone girl : a novel
    [title_medium] => 
    [callnumber] => Fic FLYNN
    [pub_year] => 2012
)

//polaris_bib_tags

    [0] => Array
        (   
            [bibliographictagid] => 3776456
            [bibliographicrecordid] => 159983
            [sequence] => 1
            [tagnumber] => 5
            [indicatorone] =>
            [indicatortwo] =>
            [effectivetagnumber] => 5
            [subfieldsequence] => 1
            [subfield] =>
            [data] => 20120810085653.0
        )

    [1] => Array
        (   
            [bibliographictagid] => 3776457
            [bibliographicrecordid] => 159983
            [sequence] => 2
            [tagnumber] => 8
            [indicatorone] =>
            [indicatortwo] =>
            [effectivetagnumber] => 8
            [subfieldsequence] => 1
            [subfield] =>
            [data] => 111026s2012    nyu           000 1 eng
        )

    [2] => Array
        (
            [bibliographictagid] => 3776458
            [bibliographicrecordid] => 159983
            [sequence] => 3
            [tagnumber] => 10
            [indicatorone] =>
            [indicatortwo] =>
            [effectivetagnumber] => 10
            [subfieldsequence] => 1
            [subfield] => a
            [data] => 2011041525
        )

// Item Status

Array
(
    [holds] => 17
    [on_order] => 0
    [orders] => Array
        (
        )

    [items] => Array
        (
            [0] => Array
                (
                    [location] => First Floor - Audiobook
                    [loc_code] => f1dbr
                    [callnum] => ACD Fic FLYNN
                    [statusmsg] => Due September 20, 2012
                    [avail] => 0
                    [due] => 1348199999
                    [age] => adult
                    [branch] => 3
                )

            [1] => Array
                (
                    [location] => First Floor - Audiobook
                    [loc_code] => f1dbr
                    [callnum] => ACD Fic FLYNN
                    [statusmsg] => Hold Shelf
                    [avail] => 0
                    [due] => 
                    [age] => adult
                    [branch] => 3
                )

        )

)



http://www.loc.gov/marc/bibliographic/ecbdlist.html







