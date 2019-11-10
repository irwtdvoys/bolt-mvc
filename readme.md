# bolt-mvc (v0.7.1)

## Roadmap

- [.] Detect changes in "state" and only save if different
    - Each model might have a hidden hash value of it's savable data
    - check current hash calculation against it
    - Update on load / successful save
- [ ] Keep track of changed (dirty) fields
