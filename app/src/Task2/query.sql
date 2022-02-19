
SELECT id, version, content FROM `some_table` t
WHERE version IN (
    SELECT max(version) FROM `some_table` WHERE id = t.id
    )
