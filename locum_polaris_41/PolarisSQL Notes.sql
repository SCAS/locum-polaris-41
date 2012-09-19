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
    [pub_info] => [Westminister, MD] : Books on Tape, p2012.
    [pub_year] => SQL1
    [stdnum] => 0307990419
    [upc] => SQL1
    [cover_img] => 
    [download_link] => 
    [lccn] => 
    [descr] => 15 sound discs (1152 min.) : digital ; 4 3/4 in.
    [notes] => 
)


SELECT TOP 1000
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