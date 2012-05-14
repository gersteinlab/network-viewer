VERSION = 0.2.0
DIST_DIR = network-viewer-$(VERSION)
OBJ = *.html *.php README assets data

dist:
	rm -rf $(DIST_DIR)
	mkdir $(DIST_DIR) && cp -r $(OBJ) $(DIST_DIR)
	tar -pczvf $(DIST_DIR).tar.gz $(DIST_DIR)
	rm -rf $(DIST_DIR)
