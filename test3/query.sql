WITH user_properties AS (
  SELECT
    u.id AS user_id,
    u.email,
    MAX(CASE WHEN p.name = 'name' THEN up.value_string END) AS name,
    MAX(CASE WHEN p.name = 'likes' THEN up.value_int END) AS likes,
    MAX(CASE WHEN p.name = 'dob' THEN up.value_datetime END) AS dob
  FROM users u
    LEFT JOIN users_properties up ON u.id = up.user_id
    LEFT JOIN properties p ON up.property_id = p.id
  GROUP BY u.id, u.email
)

SELECT * FROM user_properties;