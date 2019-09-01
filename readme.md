# bolt-mvc (v0.6.0)

## Roadmap

- [.] Detect changes in "state" and only save if different
    - Each model might have a hidden hash value of it's savable data
    - check current hash calculation against it
    - Update on load / successful save
